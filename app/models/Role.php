<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_groups'; //'role_user';//
    /*
    public function users(){
        return $this->belongsToMany('App\User'); 
    }
    */
}
