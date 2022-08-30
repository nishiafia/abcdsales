<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salesorderitem extends Model
{
    protected $guarded = [];
   
    public function productCode()
    {
        return $this->hasManyThrough(
            'App\Groupcode',
            'App\Product',
            'id', // Foreign key on cars table...
            'id', // Foreign key on owners table...
            'pitem', // Local key on Purchaseitem table...
            'groupid' // Local key on Product table...
        );
    }
    public function inventory()
    {
        return $this->hasOne('App\Product', 'id', 'pitem');
    }
    public function pcolor()
    {
        return $this->hasOne('App\Productcolor', 'id', 'picolor');
    }
    public function psize()
    {
        return $this->hasOne('App\Productsize', 'id', 'pisize');
    }
    public function excategory()
    {
        return $this->hasOne('App\Expensecategory', 'id', 'excat');
    }
    public function variationdatadetail()
    {
        return $this->hasMany('App\Salesvariation', 'peritemid');
    }
}
