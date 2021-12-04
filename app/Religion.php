<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Religion extends Model
{
    protected $table        = 'dbo.Religion';
    protected $hidden=[''];
    public function cotacts(){
        return $this->hasMany('App\Contact','ContactID','ContactID');
    }
}
