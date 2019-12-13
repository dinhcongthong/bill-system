<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractService extends Model
{
    protected $table = 'contract_service';
    protected $fillable = [
        'contract_id', 'discount_rate', 'service_start_date', 'service_id', 'time_service', 'quantity_months', 'service_price'
    ];

    public function getServiceName () {
        return $this->belongsTo('App\Http\Models\Service', 'service_id', 'id');
    }
}
