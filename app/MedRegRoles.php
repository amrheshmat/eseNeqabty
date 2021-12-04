<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedRegRoles extends Model
{
    protected $table        = 'dbo.MedRegRoles';
    protected $primaryKey = 'RoleID';
/*
  [RoleID]
      ,[GFromYear]
      ,[GToYear]
      ,[RegStatusID]
      ,[Value]
      ,[CreationDate]
      ,[CreatorID]
      ,[ModificationDate]
      ,[ModifierID]
      ,[RegestirationFees]
      ,[RegYear]
      ,[IsEngineer]*/
    public function medRegRolesDetails(){
        return $this->hasMany('App\MedRegRolesDetails','RoleID','RoleID');
    }
}
