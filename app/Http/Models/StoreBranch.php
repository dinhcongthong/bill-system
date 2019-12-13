<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class StoreBranch extends Model
{
    protected $table = 'store_branch';
    protected $fillable = ['name', 'address', 'client_type_id', 'client_id', 'tax_code', 'store_type_id'];

    public function getClient () {
        return $this->belongsTo('App\Http\Models\Client', 'client_id', 'id');
    }
}
