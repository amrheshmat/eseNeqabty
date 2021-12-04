<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public $timestamps = false;
    protected $table = 'dbo.Contact';
    protected $primaryKey = 'ContactID';
    /*
     *  [ContactID]
      ,[OldRefID]
      ,[ContactType]
      ,[ContactGroupID]
      ,[FullName]
      ,[FirstName]
      ,[SecondName]
      ,[ThirdName]
      ,[FourthName]
      ,[FirstNameEng]
      ,[SecondNameEng]
      ,[ThirdNameEng]
      ,[FourthNameEng]
      ,[FullNameEng]
      ,[Gender]
      ,[MaritalStatusID]
      ,[ReligionID]
      ,[BirthDate]
      ,[BirthGovID]
      ,[NationalNumber]
      ,[PostalNumber]
      ,[RegisterOfficeGovID]
      ,[Address]
      ,[AddressGovID]
      ,[CityID]
      ,[Telephone]
      ,[Mobile]
      ,[Email]
      ,[SyndicateID]
      ,[InsuranceNumber]
      ,[MilitaryStatusID]
      ,[DeceaseDate]
      ,[CreatorID]
      ,[CreatDate]
      ,[ModifierID]
      ,[ModifyDate]
      ,[FirstNameNormalize]
      ,[SecondNameNormalize]
      ,[ThirdNameNormalize]
      ,[FourthNameNormalize]
      ,[FullNameNormalize]
      ,[AttachmentID]
      ,[RegisterOffice]*/

    /* protected $visible = array('ContactID','FirstName','SecondName','ThirdName','FourthName',
                                'Telephone','Mobile','Email','Gender','BirthDate','DeceaseDate',
                                'InsuranceNumber','NationalNumber','PostalNumber','Address' );
                                */
    protected $appends = [
        'EngID',
    ];
    protected $visible = ['ContactID','OldRefID','FullName','medBeneficiary','engineer','EngID'];

    /*public function religion()
    {
        return $this->belongsTo('App\Religion', 'ReligionID', 'ContactID');
    }

    public function syndicate()
    {
        return $this->belongsTo('App\Syndicate', 'SyndicateID', 'ContactID');
    }

    public function governorate()
    {
        return $this->belongsTo('App\Governorate', 'ID', 'ContactID');
    }

    public function maritalStatus()
    {
        return $this->hasOne('App\MaritalStatus', 'MaritalStatusID', 'ContactID');
    }

    public function contactType()
    {
        return $this->belongsTo('App\ContactType', 'ContactTypeID', 'ContactID');
    }*/

    public function engineer()
    {
        return $this->hasOne('App\Engineer', 'ContactID', 'ContactID');
    }

    public function medBeneficiary()
    {
        return $this->hasOne('App\MedBeneficiary', 'ContactID', 'ContactID')->where('ISDELETED','<>',1);
    }

    public function getEngIDAttribute($value)
    {
        $value = (!empty($this->engineer)) ? $this->engineer->EngID : null;
        return $value;
    }
}
