<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productsize extends Model
{
    protected $guarded = [];
    public function companydata()
    {
        return $this->hasOne('App\Company', 'id', 'companyid');
    }
}
