<?php

namespace App\Http\Models;
	
use Illuminate\Database\Eloquent\Model;
// use Spatie\Activitylog\Traits\LogsActivity;
// use Spatie\Permission\Traits\HasRoles;
// use Spatie\Activitylog\Traits\CausesActivity;
use DB;

class Service extends Model
{
    // use LogsActivity, CausesActivity;

    protected $table = 'services';
    protected $fillable = [
        'name', 'price'
    ];

    // protected static $logAttributes   = ['name', 'invoice_id', 'price', 'type'];
    // protected static $logFillable   = ['name', 'invoice_id', 'price', 'type'];

    // protected static $logFillable = true; // for ActivityLog

    static public function getTableColumns($table='services') {
        return DB::getSchemaBuilder()->getColumnListing($table);
    }
}
