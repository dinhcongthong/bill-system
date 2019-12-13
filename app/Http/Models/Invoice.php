<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Spartie\Activitylog\Traits\LogsActivity;
use DB;

class Invoice extends Model
{
    // use LogsActivity;

    protected $table = 'invoices';
    protected $fillable = [
        'contract_id', 'payment_status', 'payment_note', 'time', 'exported', 'start_date', 'grand_total_money'
    ];

    // protected static $logFillable = ['contract_id', 'service_id', 'payment_type', 'payment_status', 'payment_money', 'payment_note', 'time'];

    static public function getTableColumns($table='invoices') {
        return DB::getSchemaBuilder()->getColumnListing($table);
    }

    public function getServiceName(){
    	return $this->belongsTo('app\Http\Models\Service', 'client_id', 'id');
    }
}
