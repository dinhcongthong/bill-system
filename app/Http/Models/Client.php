<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
// use Spartie\Activitylog\Traits\LogsActivity;
use LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Client extends Model
{

    // use Spartie\Activitylog\Traits\LogsActivity;
    use SoftDeletes;
    
    protected $table = 'clients';
    protected $fillable = ['name', 'status', 'setting_id', 'company_name', 'client_in_charge', 'phone_client_in_charge'
    , 'email_client_in_charge', 'address_client_in_charge', 'client_type_id' , 'tax_code'];

    // protected $logFillable = true;

    public function getContract () {
        return $this->hasMany('App\Http\Models\Contract', 'client_id', 'id');
    }

    public function getArea()
    {
        return $this->belongsTo('App\Http\Models\Setting', 'setting_id', 'id');
    }

    // public function getClientType()
    // {
    //     return $this->belongsTo('App\Http\Models\StoreType', 'client_type_id', 'id');
    // }

    static public function getTableColumns($table='clients') {
        return DB::getSchemaBuilder()->getColumnListing($table);
    }
}
