<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactType extends Model
{
    protected $table        = 'dbo.ContactType';
   protected $hidden=[''];
    public function contacts(){
        return $this->hasMany('App\Contact','ContactID','ContactTypeID');
    }
    public function contactGroup(){
        return $this->belongsTo('App\ContactGroup','ContactroupID','ContactTypeID');
    }
}
