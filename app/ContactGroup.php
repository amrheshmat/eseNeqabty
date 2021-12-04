<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactGroup extends Model
{
    protected $table        = 'dbo.ContactGroup';
    protected $hidden=[''];
    public function contactTypes(){
        return $this->hasMany('App\ContactType','ContactTypeID','ContactGroupID');
    }
}
