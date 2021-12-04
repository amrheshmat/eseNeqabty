<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceTrack extends Model
{
    protected $table = 'dbo.InvoiceTrack';
      protected $primaryKey = 'id';
      public $timestamps = false;

/*
[id]
      ,[ReceiptNo]
      ,[HusbandCount]
      ,[SonCount]
      ,[ParentCount]
      ,[HusbandValue]
      ,[MemberValue]
      ,[MemberCount]
      ,[SonValue]
      ,[ParentValue]
      ,[ReceiptDate]
      ,[ContactID]
      ,[NewRegestrationFees]
      ,[AdminstrationFess]
      ,[ReRegestrationFees]
      ,[prizeValue]
      ,[PenaltyValue]
      ,[ServiceRequestDetId]
      ,[CreatorId]
      ,[CreatorDate]
      ,[ModifierId]
      ,[ModifierDate]
      ,[HealthCareCardFees]
*/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ReceiptNo','ReceiptDate','ContactID','AdminstrationFess','ReRegestrationFees',
        'MemberCount','MemberValue','HusbandCount','HusbandValue','SonCount','SonValue', 'ParentCount',
        'ParentValue','HealthCareCardFees','ServiceRequestDetId','CreatorId','CreatorDate','ModifierId','ModifierDate'

    ];
}
