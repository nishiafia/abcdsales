<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\HasRelationships;

class Product extends Model
{
    protected $guarded = [];
    public function groupname()
    {
        return $this->hasOne('App\Groupcode', 'id', 'groupid');
    }
    public function punittype()
    {
        return $this->hasOne('App\Productunit', 'id', 'unittype');
    }
   
    public function pcolor()
    {
        return $this->hasOne('App\Productcolor', 'id', 'productcolor');
    }
    public function psize()
    {
        return $this->hasOne('App\Productsize', 'id', 'productsize');
    }
    public function companydata()
    {
        return $this->hasOne('App\Company', 'id', 'companyid');
    }
    public function variationdatadetail()
    {
        return $this->hasMany('App\Inventoryvariation', 'inventoryid');
    }
    public function labeldata()
    {
        return $this->hasOne('App\Inventoryvariation', 'id', 'vlabelid');
    }

    public function variationdata()
    {
        return $this->hasManyThrough(
          // 'App\Variationlabel',
            'App\Variation',
            'App\Inventoryvariation',
            'inventoryid', // Foreign key on Inventoryvariation table...
           //'id',
            'id', // Variation...
            'id', // Products
           // 'vlabelid',
            'variationid', // Inventoryvariation
           // 'vlabelid',
          
        );
    }
    //use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    public function variationdata2()
    {
        return $this->hasManyThrough(
           
            'App\Variationlabel', 
            'App\Variation', // Intermediate models, beginning at the far parent (Country).
            'App\Inventoryvariation',
            'inventoryid',     // Foreign key on the "comments" table.
          
           'id',
          
              'id',
              'id',
              'vlabelid', // Foreign key on the "users" table.
              'variationid',    // Foreign key on the "posts" table.
              
         
          
        );
    }
}
