<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    public function businesscategoryname()
    {
        return $this->hasOne('App\Category', 'id', 'businesscategory');
    }
    public function city()
    {
        return $this->hasOne('App\Thana', 'id', 'thanaid');
    }
    public function companytypename()
    {
        return $this->hasOne('App\Companytype', 'id', 'companytype');
    }

    public function selectedcompany()
    {
        return $this->hasOne('App\Usercompany', 'id', 'companyid');
    }
}
