<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class StoreType extends Model
{
    protected $table = 'store_type';
    protected $fillable = ['name'];
}
