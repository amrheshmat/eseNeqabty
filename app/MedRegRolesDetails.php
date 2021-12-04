<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedRegRolesDetails extends Model
{
    protected $table        = 'dbo.MedRegRolesDetails';
    protected $primaryKey = 'ContactID';
    /*
     * [RoleDetailsID]
      ,[RelationTypeID]
      ,[RoleID]
      ,[Value]
      ,[CreationDate]
      ,[CreatorID]
      ,[ModificationDate]
      ,[MoodifierID]
     */
    public function medRegRoles(){
        return $this->belongsTo('App\MedRegRoles','RoleID','RoleID');
    }
    public function medRelationType(){
        return $this->belongsTo('App\MedRelationType','RoleID','RoleID');
    }
}
