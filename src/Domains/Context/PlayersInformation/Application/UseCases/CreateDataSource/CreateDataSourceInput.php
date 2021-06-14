<?php

namespace Domains\Context\PlayersInformation\Application\UseCases\CreateDataSource;

use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemContract;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemContractEnum;

final class CreateDataSourceInput
{

    public string $type;

    public int $remoteFileId;

    public int $companyId;

    public array $source = [];

    private array $contractItems;

    public MessageHandler $modelState;

    public function __construct(string $contract, string $type, int $remoteFileId, int $companyId, array $items)
    {
        $this->modelState = new MessageHandler();
        $this->contractItems = (new SourceItemContract($contract))->values();
        $this->type = $type;
        $this->remoteFileId = $remoteFileId;
        $this->companyId = $companyId;

        $itemsUpdated = [];

        foreach ($items as $item) {
            //If necessary, it will replace the value with default one
            $itemsUpdated[] = $this->getValues($item);
        }

        $this->source = ['additional_info' => ['type' => $type, 'remote_file_id' => $remoteFileId,  'company_id' => $companyId], 'items' => $itemsUpdated];
    }

    private function getValues(array $item)
    {
        $rowFiltered = [];

        array_walk(
            $this->contractItems,
            function ($defaultValue, $column) use (&$rowFiltered, $item) {

                $columnName = $this->contractItems[$column]['name'];

                if ($columnName != SourceItemContractEnum::UNDEFINED) {
                    if (isset($item[$column])) {
                        $value = $item[$column];
                    } else {
                        $value = $this->contractItems[$column]['value'];
                    }
                    $rowFiltered[$columnName] = $value;
                }
            }
        );

        $rowUpdated = array_merge($rowFiltered, ['invoice_id' => '', 'invoiced' => false, 'was_previously_added' => false]);
        return $rowUpdated;
    }
}
