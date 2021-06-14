<?php

namespace Domains\Context\PlayersInformation\Application\UseCases\CreateDataSource;

use Domains\CrossCutting\Infrastructure\Transaction\IUnitOfWork;
use Illuminate\Support\Facades\Log;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourced;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\SourceType;
use Domains\Context\PlayersInformation\Outbound\IDataSourceOutputPort;
use Domains\Context\PlayersInformation\Domain\Services\DataSourceItemsMapped;

final class CreateDataSourceUseCase implements ICreateDataSourceUseCase
{

    private DataSourced $dataSourced;

    private IUnitOfWork $unitOfWork;

    public IDataSourceOutputPort $outputPort;

    private DataSourceItemsMapped $dataSourceItemsMapper;

    public function __construct(DataSourced $dataSourced, DataSourceItemsMapped $dataSourceItemsMapper, IUnitOfWork $unitOfWork, IDataSourceOutputPort $outputPort)
    {
        $this->dataSourced = $dataSourced;
        $this->dataSourceItemsMapper = $dataSourceItemsMapper;
        $this->unitOfWork = $unitOfWork;
        $this->outputPort = $outputPort;
        $this->unitOfWork->beginTransaction();
    }

    public function execute(CreateDataSourceInput $input)
    {
        try {
            $dataMapped = $this->dataSourceItemsMapper->map($input->source);
            $this->dataSourced->createFrom(new SourceType($input->type),$input->remoteFileId, $input->companyId, $dataMapped['items']);

            if ($this->dataSourced->isValid()) {
                $this->outputPort->ok($this->dataSourced, $input->modelState);
                $this->unitOfWork->commit();
                return;
            }

            $input->modelState->addListError(
                $this->dataSourced->getErrors()
            );
            
        } catch (\Exception $e) {
            $input->modelState->addKeyError('integration_yardi-invoice-importer-message-UNEXPECTED_ERROR_OCURRED');
            Log::critical($e->getMessage());
        }

        $this->unitOfWork->rollback();
        $this->outputPort->invalid($input->modelState);
    }
}
