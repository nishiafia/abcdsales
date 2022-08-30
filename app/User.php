<?php
namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];
   /* public function branchdata()
    {
        return $this->hasMany('App\Branche');
    }*/
    public function cominfo()
    {
        return $this->hasManyThrough(
            'App\Company',
            'App\Usercompany',
            'userid', // Foreign key on usercompanies table...
            'id', // company...
            'id', // user
            'companyid' // usercompanies
        );
    }
    public function systemname()
    {
        return $this->hasOne('App\User', 'id', 'systemid');
    }
    public function manager()
    {
        return $this->hasOne('App\User', 'id', 'systemid');
    }
    public function comname()
    {
        return $this->hasOne('App\Company', 'id', 'companyid');
    }

    
}