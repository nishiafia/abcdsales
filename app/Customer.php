<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];
    public function pname()
    {
        return $this->hasOne('App\Partner', 'id', 'partnertype');
    }
}
