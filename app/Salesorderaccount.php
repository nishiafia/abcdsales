<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salesorderaccount extends Model
{
    protected $guarded = [];
    public function getbankname()
    {
        return $this->hasOne('App\Bank', 'id', 'bankid');
    }
}
