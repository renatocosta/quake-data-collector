<?php

namespace Tests\Context\PlayersInformation;

use Domains\CrossCutting\Domain\Application\Event\Bus\DomainEventBus;
use Domains\CrossCutting\Domain\Infrastructure\Transaction\IUnitOfWork;
use Mockery;
use Domains\Context\PlayersInformation\Application\EventHandlers\DataSource\DataSourceWasCreatedEventHandler;
use Domains\Context\PlayersInformation\Application\Services\DataSource\AssetCodeMissing;
use Domains\Context\PlayersInformation\Application\Services\DataSource\AssetCodeNotFound;
use Domains\Context\PlayersInformation\Application\Services\DataSource\DefaultItemStatusCriteria;
use Domains\Context\PlayersInformation\Application\Services\DataSource\ExternalInvoiceIdMissing;
use Domains\Context\PlayersInformation\Application\Services\DataSource\InvoiceValueChanged;
use Domains\Context\PlayersInformation\Application\Services\DataSource\PoNumberDuplicate;
use Domains\Context\PlayersInformation\Application\Services\DataSource\PoNumberMissing;
use Domains\Context\PlayersInformation\Application\Services\DataSource\PoNumberNotFound;
use Domains\Context\PlayersInformation\Application\Services\DataSource\RecurringRegistryEntries;
use Domains\Context\PlayersInformation\Application\Services\DataSource\SuccessfullyImportedPreviously;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSource;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\DataSourceItemNotFound;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceNotFound;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\IDataSourceRepository;
use Domains\Context\PlayersInformation\Domain\Services\InvoiceDataIntegration;
use Domains\Context\PlayersInformation\Infrastructure\Framework\Entities\InvoiceDataSource;
use stdClass;

trait PlayersInformationFactoryTestProvider
{

    protected $messageHandler;

    protected $dataSource;

    protected $domainEventBus;

    protected $dataSourceRepository;

    protected $assetModel;

    protected $invoiceDataSource;

    protected $invoiceDataSourceItems;

    protected $invoiceDataIntegration;

    protected $statusItemFlow;

    protected $unitOfWork;

    public function loadPlayersInformationDependencies()
    {
        $this->createUnitOfWork();
        $this->createRepository();
        $this->createDomainEventBus();
        $this->createDataSource();
        $this->createAssetModel();
        $this->createInvoiceDataIntegration();
        $this->createStatusCriteria();
        $this->createInvoiceDataSource();
        $this->createInvoiceDataSourceItems();
    }

    protected function createRepository()
    {
        $this->dataSourceRepository = Mockery::mock(IDataSourceRepository::class, ['create' => [new DataSourceNotFound()], 'createItems' => [new DataSourceItemNotFound()]]);
    }

    protected function createUnitOfWork()
    {
        $this->unitOfWork = Mockery::mock(IUnitOfWork::class);
        $this->unitOfWork->shouldReceive('beginTransaction');
        $this->unitOfWork->shouldReceive('commit');
        $this->unitOfWork->shouldReceive('rollback');
    }

    protected function createDomainEventBus()
    {
        $this->domainEventBus = new DomainEventBus();
        $this->domainEventBus->subscribe(new DataSourceWasCreatedEventHandler($this->dataSourceRepository));
    }

    protected function createDataSource()
    {
        $this->dataSource = new DataSource($this->domainEventBus);
    }
    protected function createInvoiceDataSource()
    {
        $this->invoiceDataSource = Mockery::mock(InvoiceDataSource::class);
    }

    protected function createInvoiceDataSourceItems()
    {
        $this->invoiceDataSourceItems = Mockery::mock('\Domains\Context\PlayersInformation\Infrastructure\Framework\Entities\InvoiceDataSourceItems');
        $this->invoiceDataSourceItems->shouldReceive('replicate->find')->andReturn(new stdClass());
        $this->invoiceDataSourceItems->shouldReceive('getAttribute');
    }

    protected function createAssetModel()
    {
        $this->assetModel = Mockery::mock('\Domains\Context\Asset\Entities\Asset');
        $this->assetModel->shouldReceive('where->first')->andReturn(new stdClass());
    }

    protected function createAssetModelNotFound()
    {
        $this->assetModel = Mockery::mock('\Domains\Context\Asset\Entities\Asset');
        $this->assetModel->shouldReceive('where->first')->andReturn(null);
    }

    protected function createInvoiceDataIntegration()
    {
        $this->invoiceDataIntegration = Mockery::mock(InvoiceDataIntegration::class)->shouldAllowMockingProtectedMethods();
        $this->invoiceDataIntegration->shouldReceive('createInvoice')->with(new DataSourceItemNotFound())->andReturn(null);
        $this->invoiceDataIntegration->shouldReceive('collectItems');
        $this->invoiceDataIntegration->shouldReceive('create');
    }

    protected function createStatusCriteria()
    {

        $this->statusItemFlow = new AssetCodeMissing();
        $this->statusItemFlow->linkNext(new PoNumberMissing())
            ->linkNext(new ExternalInvoiceIdMissing())
            ->linkNext(new AssetCodeNotFound($this->assetModel))
            ->linkNext(new PoNumberDuplicate())
            ->linkNext(new PoNumberNotFound())
            ->linkNext(new RecurringRegistryEntries())
            ->linkNext(new InvoiceValueChanged())
            ->linkNext(new SuccessfullyImportedPreviously())
            ->linkNext(new DefaultItemStatusCriteria());
    }
}
