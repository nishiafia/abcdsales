<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usercompany extends Model
{
    protected $guarded = [];
    public function teamcompanyname()
    {
        return $this->hasOne('App\Company', 'id', 'companyid');
    }
}
