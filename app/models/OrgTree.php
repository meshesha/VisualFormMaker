<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class OrgTree extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organization_tree';

    
    public function user(){
        return $this->belongsTo('App\User'); 
    }
}
