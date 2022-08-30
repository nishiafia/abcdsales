<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchaseorder extends Model
{
    protected $guarded = [];
    public function vendordata()
    {
        return $this->hasOne('App\Customer', 'id', 'vendorid');
    }
    public function refporder()
    {
        return $this->hasOne('App\Purchaseorder', 'id', 'refpo');
    }
    public function companydata()
    {
        return $this->hasOne('App\Company', 'id', 'companyid');
    }
}
