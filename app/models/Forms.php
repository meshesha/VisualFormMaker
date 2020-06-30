<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Forms extends Model
{
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    //https://laravel.com/docs/7.x/eloquent-relationships#one-to-many
    public function submitted(){
        return $this->hasMany('App\models\SubmittedForms', 'form_id', 'id');
    }
}
