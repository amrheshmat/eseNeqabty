<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DboContact extends Model
{
    protected $table        = 'dbo.Contact';
    protected $primaryKey   = 'ContactID';
    public $timestamps      = false;
    protected $fillable = ['FullName'];

    //Realtionship
    public function DboMedBeneficiary(){return $this->hasOne('App\DboMedBeneficiary','ContactID','ContactID');}

}