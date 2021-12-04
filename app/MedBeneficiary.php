<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedBeneficiary extends Model
{
    protected $table = 'dbo.MedBeneficiary';
    protected $primaryKey = 'BenID';
    public $timestamps = false;

    /*
     [BenID]
      ,[BENNAME]
      ,[BENNAMEENG]
      ,[NATIONALID]
      ,[BIRTHDATE]
      ,[GENDER]
      ,[MOBILE]
      ,[ADDRESS]
      ,[RELATEDTO]
      ,[RELATIONTYPE]
      ,[PIC]
      ,[ISDELETED]
      ,[BENNAME1]
      ,[BENNAME2]
      ,[BENNAME3]
      ,[BENNAME4]
      ,[RegStatusID]
      ,[MedMemberTypeID]
      ,[ContactID]
      ,[WifeTypeCaseID]
      ,[lastMedCareyear]
      ,[GraduationYear]
      ,[WifeCasePic]
      ,[FollowerOrder]
      ,[RegistrationDate]
      ,[RegistrationYear]
      ,[EngRelationID]
      ,[creatorID]
      ,[creationDate]
      ,[ModifierID]
      ,[ModificationDate]
      ,[LastAskRenewYear]
      ,[LastPrintCardYear]
      ,[LastPrintCouponYear]
      ,[ExceptionReason]
      ,[DelivieryId]
      ,[oldbenid] =>
      ,[PrintCoponID]
      ,[ISException]
      ,[OldBenIDEmp]
      ,[Notes]
      ,[isActive]
     */
   /* protected $visible = ['BenID', 'ContactID', 'oldbenid', 'BENNAME', 'RELATIONTYPE', 'lastMedCareyear', 'FollowerOrder',
        'RegistrationDate', 'LastAskRenewYear', 'LastPrintCardYear', 'LastPrintCouponYear',
        'medBeneficiaries', 'ISDELETED', 'chargeItemID','chargeItemName'];*/

    protected $fillable=['LastAskRenewYear','lastMedCareyear','ModifierID','ModificationDate'];

    protected $appends = [
        'chargeItemID', 'chargeItemName'
    ];
    public $with=['chargeItemsKey'];

    public function contact()
    {
        return $this->belongsTo('App\Contact', 'ContactID', 'BenID');
    }

    public function medRelationType()
    {
        return $this->belongsTo('App\MedRelationType', 'RELATIONTYPE', 'BenID');
    }

    public function medBeneficiaries()
    {
        return $this->hasMany(MedBeneficiary::class, 'RELATEDTO', 'BenID')->where('ISDELETED', '<>', 1);
    }

    public function chargeItemsKey()
    {
        return $this->hasOne('App\ChargeItemsKey', 'ChargeItemID', 'chargeItemID');
    }

    public function getChargeItemIDAttribute($value)
    {
        switch ($this->RELATIONTYPE) {
            case 1://case of engineer/main member
                $value = 5;
                break;
            case 2://case of wife
                $value = 1032;
                break;
            case 3://case of children
                $value = 1030;
                break;
            case 4://case of parents
                $value = 1031;
                break;
        }
        return $value;
    }

    public function getChargeItemNameAttribute($value)
    {
        $value = (!empty($this->chargeItemsKey)) ? $this->chargeItemsKey->Description : null;
        return $value;
    }

}
