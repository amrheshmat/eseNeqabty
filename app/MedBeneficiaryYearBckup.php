<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedBeneficiaryYearBckup extends Model
{
 protected $table = 'dbo.MedBeneficiaryYearBckup';
    protected $primaryKey ='ID';
    public $timestamps = false;
    /*
    [ID]
      ,[ContactID]
      ,[ReceiptNo]
      ,[Year]
      ,[Count]
      ,[CreatorID]
      ,[CreationDate]
      ,[ModifierID]
      ,[ModificationDate]
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ContactID','ReceiptNo','Year','Count','CreatorID','CreationDate'];


    /**
     * The attributes that should be visible
     *
     * @var array
     */
    protected $visible = ['ContactID','ReceiptNo','Year','Count','CreatorID','CreationDate'];

}
