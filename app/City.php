<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table        = 'dbo.City';
    protected $hidden=[''];
    public function governorate(){
        return $this->belongsTo('App\Governorate','ID','ID');
    }
}
