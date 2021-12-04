<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaritalStatus extends Model
{
    protected $table        = 'dbo.MaritalStatus';
    protected $hidden=[''];
    public function contacts(){
        return $this->belongsTo('App\Contact','ContactID','ContactID');
    }
}
