<?php

namespace Tests\Context\YardiIntegration;

use Domain\CrossCutting\Application\Event\Bus\DomainEventBus;
use DG\BypassFinals;
use Illuminate\Http\UploadedFile;
use Domains\Context\LogHandler\Application\UseCases\Spreadsheet\ExtractSpreadsheetInput;
use Domains\Context\InvoiceImporter\Application\EventHandlers\DataSource\DataSourceWasCreatedEventHandler;
use Domains\Context\InvoiceImporter\Application\Services\DataSource\DataSourceItemsMapper;
use Domains\Context\InvoiceImporter\Application\UseCases\CreateDataSource\CreateDataSourceInput;
use Domains\Context\InvoiceImporter\Application\UseCases\CreateDataSource\CreateDataSourceUseCase;
use Domains\Context\InvoiceImporter\Domain\Model\DataSource\DataSourceItem\DataSourceItem;
use Domains\Context\InvoiceImporter\Domain\Model\DataSource\DataSourceItem\SourceItemContractEnum;
use Domains\Context\InvoiceImporter\Domain\Specifications\DataSourceItemCanBeATrade;
use Domains\Context\InvoiceImporter\Domain\Specifications\DataSourceItemWasPreviouslyAdded;
use Domains\Context\InvoiceImporter\Domain\Specifications\DataSourceItemWasPreviouslyAddedInRepo;
use Domains\Context\InvoiceImporter\Domain\Specifications\DataSourceRecurringRegistryEntries;
use Domains\Context\Trade\Entities\PurchaseOrder;
use Tests\Context\InvoiceImporter\CreateDataSourceOutputPortMocked;
use Tests\TestCase;

class EndToEndTest extends TestCase
{
    use \Tests\Context\LogHandler\LogHandlerFactoryTestProvider;
    use \Tests\Context\LogHandler\LogHandlerTestProvider;
    use \Tests\Context\InvoiceImporter\InvoiceImporterFactoryTestProvider;
    use \Tests\Context\InvoiceImporter\InvoiceImporterTestProvider;

    public static function setUpBeforeClass(): void
    {
        BypassFinals::enable();
    }

    public function setup(): void
    {
        parent::setUp();
        $this->loadDependencies();
        $this->loadInvoiceImporterDependencies();
        $this->loadProviders();
    }

    public function testShouldBeAbleToImportUploadedFileDataSuccessfully()
    {

        $validFile = sprintf('%s%s%s', dirname(__FILE__),  '/../LogHandler/bucket/', $this->uploadFiles['valid_file']);
        $file = new UploadedFile($validFile, $validFile);
        $input = new ExtractSpreadsheetInput($file, $this->messageHandler);
        $this->extractSpreadsheetUseCase->execute($input);
        $result = $this->extractSpreadsheetUseCase->outputPort->result();
        $this->assertTrue($result->isValid());

        if ($result->isValid()) {
            return iterator_to_array($this->extractSpreadsheetUseCase->outputPort->dataSourced->getRows());
        }
    }

    /**
     * @depends testShouldBeAbleToImportUploadedFileDataSuccessfully
     */
    public function testShouldBeAbleToImportDataSourceSuccessfully($etlData)
    {
        $dataSourcedAsASuccessStatus = new DataSourceItem($this->validItemStatus['asset_code'], $this->validItemStatus['po_number'], $this->validItemStatus['amount'], '', $this->validItemStatus['external_invoice_id'], $this->validItemStatus['raw_data'], $this->validItemStatus['invoice_id'], true, '', $this->validItemStatus['status']);
        $dataSourcedAsASuccessStatus->setId($this->validItemStatus['id']);
        $this->dataSourceRepository->shouldReceive('findByAssetCodeAndPoNumberAndExternalInvoiceId')->andReturn($dataSourcedAsASuccessStatus);

        $dataSourceRecurringRegistryEntries = new DataSourceRecurringRegistryEntries();
        $dataSourceItemWasPreviouslyAddedInRepo = new DataSourceItemWasPreviouslyAddedInRepo($this->dataSourceRepository);
        $dataSourceItemsMapper = new DataSourceItemsMapper($this->statusItemFlow, new DataSourceItemWasPreviouslyAdded($dataSourceItemWasPreviouslyAddedInRepo, $dataSourceRecurringRegistryEntries), new DataSourceItemCanBeATrade(new PurchaseOrder()));

        $outputPort = new CreateDataSourceOutputPortMocked();
        $domainEventBus = new DomainEventBus();
        $domainEventBus->subscribe(new DataSourceWasCreatedEventHandler($this->dataSourceRepository));
        $createDataSourceUseCase =  new CreateDataSourceUseCase($this->dataSource, $dataSourceItemsMapper, $this->unitOfWork, $outputPort);
        $input = new CreateDataSourceInput(SourceItemContractEnum::V1000, 'YARDI_EXPORT_EXCEL', 12445, 1, $etlData);
        $createDataSourceUseCase->execute($input);
        $result = $createDataSourceUseCase->outputPort->result();
        $this->assertTrue($result->isValid());
    }
}
