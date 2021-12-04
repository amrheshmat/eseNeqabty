<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedRelationType extends Model
{
    protected $table        = 'dbo.MedRelationType';
    public function medBeneficiaries(){
        return $this->hasMany('App\MedBeneficiary','RelationTypeID','RelationTypeID');
    }
    public function medRegDetails(){
        return $this->hasMany('App\MedRegRolesDetails','RelationTypeID','RolDetailsID');
    }
}
