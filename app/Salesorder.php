<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salesorder extends Model
{
    protected $guarded = [];
    public function customerdata()
    {
        return $this->hasOne('App\Customer', 'id', 'customerid');
    }
    public function agentdata()
    {
        return $this->hasOne('App\Deliveryagent', 'id', 'agentid');
    }
    public function refsorder()
    {
        return $this->hasOne('App\Salesorder', 'id', 'refso');
    }
    public function companydata()
    {
        return $this->hasOne('App\Company', 'id', 'companyid');
    }
}
