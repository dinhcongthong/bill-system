<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Models\StoreBranch;
use App\Http\Models\StoreType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DB;

class Contract extends Model
{
    use SoftDeletes;

    protected $table = 'contracts';
    protected $fillable = [
        'client_id', 'contract_code', 'store_branch_id', 'company_phone', 'tax_code', 'poste_in_charge', 'representative', 'status', 'start_date'
        , 'end_date', 'vat', 'vat_status', 'payment_times', 'invoice', 'contract_note', 'quantity_months', 'payment_type', 'exchange_rate'
    ];

    public function getStoreTypeAttribute() {
        if (!empty($this->store_branch_id)) {
            $storeBranchArr = explode(',', $this->store_branch_id);
            $store_branch_ids = StoreBranch::whereIn('id', $storeBranchArr)->select('id', 'store_type_id')->get();
            
            $store_type_ids = [];
            foreach($store_branch_ids as $item) {
                $type_id_arr = explode(',', $item->store_type_id);
                $store_type_ids = array_unique(array_merge($store_type_ids, $type_id_arr));
            }
        } else {
            $client = $this->getClientName;

            if(!is_null($client)) {
                $store_type_ids = explode(',', $client->client_type_id);
            } else {
                $store_type_ids = [];
            }
        }
        $store_types_list = StoreType::whereIn('id', $store_type_ids)->select('name')->get()->pluck('name')->toArray();
        return implode(',', $store_types_list);
    }

    static public function getTableColumns($table='contracts') {
        return DB::getSchemaBuilder()->getColumnListing($table);
    }

    public function getClientName () {
        return $this->belongsTo('app\Http\Models\Client', 'client_id', 'id');
    }

    public function getInvoice () {
        return $this->hasMany('App\Http\Models\Invoice', 'contract_id', 'id');
    }

    public function getArea()
    {
        return $this->belongsTo('App\Http\Models\Setting', 'setting_id', 'id');
    }
}
