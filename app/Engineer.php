<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Engineer extends Model
{
  protected $table= 'dbo.Engineer';
  protected $primaryKey = 'EngID';
  public $timestamps = false;
    /*
    [EngID]
      ,[ContactID]
      ,[RefID]
      ,[OldRefID]
      ,[ConsultID]
      ,[IsArabs]
      ,[PassportNumber]
      ,[PassportNationalityID]
      ,[NationalityID]
      ,[MotherNationalityID]
      ,[UniversityID]
      ,[FacultyID]
      ,[SpecialityID]
      ,[GraduationYear]
      ,[GraduationPhase]
      ,[GraduationGradeID]
      ,[ProjectGradeID]
      ,[LEducationLevelID]
      ,[LEduLevelDate]
      ,[WorkStatus]
      ,[WorkPlace]
      ,[WorkTelephone]
      ,[WorkAddress]
      ,[WorkGovID]
      ,[WorkFax]
      ,[Job]
      ,[LastRegYear]
      ,[LastConsYear]
      ,[ResidentStatus]
      ,[RegStatusID]
      ,[Status]
      ,[RegDate]
      ,[ConsultantRegDate]
      ,[LastRegDate]
      ,[EngRecordID]
      ,[LastConsDate]
      ,[CreatorID]
      ,[CreatDate]
      ,[ModifierID]
      ,[ModifyDate]
      ,[sectionid]
      ,[IsDoctor]
      ,[ConsultantStatusID]
    */



    protected $visible=['OldRefID','EngID','ContactID','ConsultID','contact','regStatus','NationalityID',
        'GraduationYear','LastRegDate','LastConsYear','RegStatusID','Status'];
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['LastRegYear','LastRegDate','LastConsYear','LastConsDate'];

    public function contact(){
        return $this->belongsTo('App\Contact','ContactID','ContactID');
    }

    public function regStatus(){
        return $this->hasOne('App\RegStatus','RegStatusID','RegStatusID');
    }
}
