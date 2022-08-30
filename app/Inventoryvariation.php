<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventoryvariation extends Model
{
     protected $guarded = [];

     public function labeldata()
     {
 
    return $this->hasOne('App\Variationlabel','id', 'vlabelid');
  }
  public function valdata()
  {

 return $this->hasOne('App\Variation','id','variationid');
}
     
    
}
