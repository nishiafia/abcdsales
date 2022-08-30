<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    protected $guarded = [];
    public function companydata()
    {
        return $this->hasOne('App\Company', 'id', 'companyid');
    }

    public function labeldata()
    {
        return $this->hasOne('App\Variationlabel', 'id', 'vlabelid');
    }
}