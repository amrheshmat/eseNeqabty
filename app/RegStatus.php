<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegStatus extends Model
{
    protected $table        = 'dbo.RegStatus';
    protected $hidden=[''];
    public function engineer(){
        return $this->belongsTo('App\Engineer','EngID','EngID');
    }
}
