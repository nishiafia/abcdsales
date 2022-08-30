<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchaseaccount extends Model
{
    protected $guarded = [];
    public function getbankname()
    {
        return $this->hasOne('App\Bank', 'id', 'bankid');
    }
}
