<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsultantOffice extends Model
{
    protected $table        = 'dbo.ConsultantOffice';
    protected $hidden=[''];
    public function governorate(){
        return $this->belongsTo('App\Governorate','ID','ID');
    }

}
