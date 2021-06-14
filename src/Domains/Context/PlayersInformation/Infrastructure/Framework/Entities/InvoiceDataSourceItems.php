<?php

namespace Domains\Context\PlayersInformation\Infrastructure\Framework\Entities;

use App\Extensions\BaseModel as Model;
use Domains\Context\Core\Entities\Invoice;
use Domains\Context\Trade\Entities\Trade;
use Domains\Context\Trade\Entities\TradeInvoice;

class InvoiceDataSourceItems extends Model
{

    protected $table = 'invoice_data_source_item';

    protected $fillable = ['asset_code', 'po_number', 'amount', 'external_invoice_id', 'status', 'raw_data'];

    public $timestamps = false;

    public function source()
    {
        return $this->belongsTo(InvoiceDataSource::class, 'invoice_data_source_id');
    }

    public function tradeInvoice()
    {
          return $this->belongsTo(TradeInvoice::class, 'invoice_id', 'invoice_id');
    }
}
