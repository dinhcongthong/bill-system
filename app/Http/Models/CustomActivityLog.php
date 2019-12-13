<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity as ActivityModels;
use App\Http\Models\User;

class CustomActivityLog extends ActivityModels
{
    protected $fillable = [
        'log_name', 'description', 'subject_id', 'subject_type', 'causer_id', 'causer_type', 'properties'
    ];

    public function getUser() {
        return $this->belongsTo('App\Http\Models\User', 'causer_id', 'id');
    }
}