<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
    protected $guarded = [];
    public function vendordata()
    {
        return $this->hasOne('App\Customer', 'id', 'vendorid');
    }
    public function pname()
    {
        return $this->hasOne('App\Partner', 'id', 'partnertype');
    }
}
