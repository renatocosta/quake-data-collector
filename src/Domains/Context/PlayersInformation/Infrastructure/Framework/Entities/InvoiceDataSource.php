<?php

namespace Domains\Context\PlayersInformation\Infrastructure\Framework\Entities;

use App\Extensions\BaseModel as Model;
use Domains\Context\Core\Entities\RemoteFile;

class InvoiceDataSource extends Model
{

    protected $table = 'invoice_data_source';

    protected $dateFormat = 'U';

    protected $fillable = ['type', 'company_id', 'remote_file_id'];

    public function items()
    {
        return $this->hasMany(InvoiceDataSourceItems::class, 'invoice_data_source_id');
    }

    public function remoteFile()
    {
        return $this->belongsTo(RemoteFile::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $model->created_by = \Auth::User()->id;
        });

    }
}
