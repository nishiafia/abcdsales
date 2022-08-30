<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $guarded = [];
   
   /* public function contact_person()
    {
        return $this->hasOne('App\User', 'id', 'branch_contact_person');
    }
    public function supervisor()
    {
        return $this->hasOne('App\User', 'id', 'branch_supervisor');
    }*/
}