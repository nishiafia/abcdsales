<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $guarded = [];
    public function businesscategoryname()
    {
        return $this->hasOne('App\Category', 'id', 'businesscategory');
    }
}
