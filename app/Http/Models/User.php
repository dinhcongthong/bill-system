<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    
    protected $connection = 'postevn';
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'name', 'email', 'password', 'type_id', 'first_name', 'first_kata_name', 'last_kata_name', 'avatar', 'gender_id', 'birthday'
        , 'residence_id', 'occupation_id', 'phone', 'secretquestion_id', 'answer', 'is_news_letter'
    ];
    
    /**
    * Accessor to get Fullname of user
    */
    public function getFullNameAttribute() {
        if(empty($this->first_kata_name) && empty($this->last_kata_name)) {
            return $this->first_kata_name.' '.$this->last_kata_name;
        } else {
            return $this->first_name . ' ' . $this->name;
        }
    }
    
    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /**
    * The attributes that should be cast to native types.
    *
    * @var array
    */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    static public function getTableColumns($table='users') {
        return DB::getSchemaBuilder()->getColumnListing($table);
    }

    /**
     * Relationship n - 1
     */
    public function type()
    {
        return $this->belongsTo('UserType', 'type_id', 'id');
    }
}
