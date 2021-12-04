<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedBeneficiaryBackup extends Model
{
 protected $table = 'dbo.MedBeneficiaryBackup';
    protected $primaryKey ='ID';
    public $timestamps = false;
    /*
    [ID]
      ,[BeneficiaryID]
      ,[LastRegyear]
      ,[ReceiptNo]
      ,[creationDate]
      ,[CreatorID]
      ,[ModificationDate]
      ,[ModifierID]
      ,[ServiceReqDetID]
      ,[IsException]
      ,[LastAskYear]
     */


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['BeneficiaryID','LastRegyear','ReceiptNo','creationDate','CreatorID','ServiceReqDetID','LastAskYear',
    'ModificationDate','ModifierID'];


    /**
     * The attributes that should be visible
     *
     * @var array
     */
    protected $visible = ['BeneficiaryID','LastRegyear','ReceiptNo','creationDate','CreatorID','ServiceReqDetID','LastAskYear'];

}
