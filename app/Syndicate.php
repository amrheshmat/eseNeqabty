<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Syndicate extends Model
{
    protected $table        = 'dbo.Syndicate';
    protected $hidden=[''];
    public function contacts(){
        return $this->hasMany('App\Contact','ContactID','ContactID');
    }
}
