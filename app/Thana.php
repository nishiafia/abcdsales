<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thana extends Model
{
    protected $guarded = [];
    public function districtdata()
    {
        return $this->hasOne('App\District', 'id', 'districtid');
    }
}
