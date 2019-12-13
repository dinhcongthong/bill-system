<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Setting extends Model
{
    protected $table = 'setting';
    use SoftDeletes;
    protected $fillable = [
        'name_poste', 'address_poste', 'email_poste', 'area_name', 'path_logo_file', 'tax_code', 'phone_poste', 'vat_rate', 'exchange_rate'
        , 'bank_name', 'acc_bank_name', 'acc_bank_number', 'parent_id'
    ];

    public function getCities() {
        return $this->hasMany('App\Http\Models\Setting', 'parent_id', 'id');
    }

    public function getArea () {
        return $this->belongsTo('App\Http\Models\Area', 'area_id', 'id');
    }

    static public function getTableColumns($table='setting') {
        return DB::getSchemaBuilder()->getColumnListing($table);
    }
}
