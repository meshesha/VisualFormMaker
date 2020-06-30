<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class UsersGroup extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_groups';
    
    public function users(){
        return $this->belongsToMany('App\User', 'role_user','role_id'); 
    }
    
    public function roles(){
        return $this->belongsToMany('App\models\Role','role_user','role_id'); 
    }
}
