<?php

namespace Domains\Context\PlayersInformation\Infrastructure\DataAccess\Repositories;

use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourced;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\DataSourcedItem;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\DataSourceItem;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\DataSourceItemNotFound;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\IDataSourceRepository;
use Domains\Context\PlayersInformation\Infrastructure\Framework\Entities\InvoiceDataSource;
use Domains\Context\PlayersInformation\Infrastructure\Framework\Entities\InvoiceDataSourceItems;
use Domains\CrossCutting\Model\ValueObjects\Money\Currency;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatus;

final class DataSourceRepository implements IDataSourceRepository
{

    private InvoiceDataSource $modelInvoiceDataSource;

    private InvoiceDataSourceItems $modelInvoiceDataSourceItems;

    private const TOTAL_PER_PAGE = 20;

    public function __construct(InvoiceDataSource $invoiceDataSource, InvoiceDataSourceItems $invoiceDataSourceItems)
    {
        $this->modelInvoiceDataSource = $invoiceDataSource;
        $this->modelInvoiceDataSourceItems = $invoiceDataSourceItems;
    }

    public function findAll()
    {
        return  $this->modelInvoiceDataSource
            ->select('id', 'type', 'remote_file_id', 'created_at')
            ->with(['remoteFile' => function ($query) {
                $query->select('id', 'name', 'size', 'mime_type');
            }])
            ->has('remoteFile')
            ->with(['items' => function ($query) {
                $query->select(\DB::raw('invoice_data_source_id, count(invoice_data_source_id) as total, SUM(case when status = \'SUCCESS\' OR status = \'SUCCESSFULLY_IMPORTED_PREVIOUSLY\' then 1 else 0 end) as success, SUM(case when status != \'SUCCESS\' AND status != \'SUCCESSFULLY_IMPORTED_PREVIOUSLY\' then 1 else 0 end) as errors'))
                    ->groupBy('invoice_data_source_id');
            }])
            ->has('items')
            ->where('company_id', \Auth::user()->company->id)
            ->orderBy('id', 'DESC')
            ->paginate(self::TOTAL_PER_PAGE);
    }

    public function update(DataSourced $dataSourced): void
    {
        // Not implemented yet
    }

    public function create(DataSourced $dataSourced): void
    {
        $this->modelInvoiceDataSource->fill(['type' => $dataSourced->getType(), 'company_id' => $dataSourced->getCompanyId(), 'remote_file_id' => $dataSourced->getRemoteFileId()]);
        $this->modelInvoiceDataSource->save();
        $dataSourced->setId($this->modelInvoiceDataSource->id);
    }

    public function findByItemsByAssetCode(string $code): array
    {
        return $this->modelInvoiceDataSourceItems
            ->where('asset_code', $code)
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
    }

    public function findByItemsByInvoiceDataSourceId(int $id)
    {

        return $this->modelInvoiceDataSource
            ->select('id', 'remote_file_id')
            ->with(['items' => function ($query) {
                $query->select('id', 'asset_code', 'po_number', 'amount', 'external_invoice_id', 'status', 'invoice_data_source_id', 'raw_data', 'invoice_id');
                $query->with(['tradeInvoice' => function ($query) {
                    $query->select('trade_id', 'invoice_id');
                    $query->with(['trade_' => function ($query) {
                        $query->select('id', 'project_id');
                    }]);
                }]);
            }])
            ->has('items')
            ->with(['remoteFile' => function ($query) {
                $query->select('id', 'name');
            }])
            ->has('remoteFile')
            ->find($id);
    }

    public function findByAssetCodeAndPoNumberAndExternalInvoiceId(string $asset, string $poNumber, string $externalInvoice): DataSourcedItem
    {

        $queryItem = $this->modelInvoiceDataSourceItems
            ->with(['source' => function ($query) {
                $query->where('company_id', \Auth::user()->company->id);
            }])
            ->has('source')
            ->where('asset_code', $asset)
            ->where('po_number', $poNumber)
            ->where('external_invoice_id', $externalInvoice)
            ->whereNotNull('invoice_id')
            ->orderBy('id', 'asc')
            ->first();

        if (!is_null($queryItem)) {
            $invoiceId = $queryItem->invoice_id > 0 ? $queryItem->invoice_id : 0;
            $wasPreviouslyInvoiced = $invoiceId > 0 ? true : false;
            return new DataSourceItem($queryItem->asset_code, $queryItem->po_number, new Currency($queryItem->amount), '', $queryItem->external_invoice_id, $queryItem->raw_data, $invoiceId, $wasPreviouslyInvoiced, '', new SourceItemStatus($queryItem->status));
        }

        return new DataSourceItemNotFound();
    }

    public function updateByItem(DataSourcedItem $dataSourcedItem): void
    {
        // Not implemented yet
    }

    public function createItems(\SplDoublyLinkedList $items): void
    {

        for ($items->rewind(); $items->valid(); $items->next()) {
            $item = $items->current();
            $modelInvoiceDataSourceItems = $this->modelInvoiceDataSourceItems->replicate();

            $dataSourceItem = $modelInvoiceDataSourceItems->fill(['asset_code' => $item->getAssetCode(), 'po_number' => $item->getPoNumber(), 'amount' => $item->getAmount(), 'external_invoice_id' => $item->getExternalInvoiceId(), 'status' => $item->getStatus(), 'raw_data' => $item->getRaw()]);
            $dataSaved = $this->modelInvoiceDataSource->items()->save($dataSourceItem);
            $item->setId($dataSaved->id);
        }
    }
}
