<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    protected $table        = 'dbo.Governorate';
    protected $hidden=[''];
    public function cities(){
        return $this->hasMany('App\City','CITYID','CITYID');
    }
    public function consultantOffices(){
        return $this->hasMany('App\ConsultantOffice','OfficeID','OfficeID');
    }
    public function contacts(){
        return $this->hasMany('App\Contact','ContactID','ContactID');
    }
}
