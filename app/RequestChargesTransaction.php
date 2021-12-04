<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestChargesTransaction extends Model
{
    protected $table        = 'dbo.RequestChargesTransaction';
    protected $primaryKey = 'ChargeItemID';
    public $timestamps = false;
/*
[TransactionID] => primary
      ,[ChargeItemID] => chargeItem foreign
      ,[CreatorID] => which id ?? [EngID]/[ContactID]/[RefID]/[OldRefID]
      ,[Quantity] always 1?
      ,[Type] => what is C ?
      ,[UnitPrice] calculated charge ?
      ,[TotalPrice] always the same as UnitPrice?
      ,[ServiceReqDetID] ServiceRequestDetails foreign
      ,[Active] always null?
      ,[CreatDate] current date time
      ,[ModifierID] => which id ?? [EngID]/[ContactID]/[RefID]/[OldRefID]
      ,[ModifyDate]
*/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ChargeItemID', 'UnitPrice','TotalPrice','Quantity',
        'ServiceReqDetID','CreatDate','ModifierID','ModifyDate'
    ];

    protected $appends = [
        'name'
    ];

    protected $visible = ['UnitPrice','name','TotalPrice','Quantity'];

    //set the default values for attributes
    protected $attributes=['Quantity'=>1,'CreatorID'=>4543,'Type'=>'C'];


    /**
     * many to one relation with ServiceRequestDetails
     */
    public function serviceRequestDetails()
    {
        return $this->belongsTo(ServiceRequestDetails::class,'ServiceReqDetID','ServiceReqDetID');
    }

   /* public function chargeItemsKey()
    {
        return $this->hasOne('App\ChargeItemsKey', 'ChargeItemID', 'ChargeItemID');
    }*/

    /**
     * many to one relation to chargeItem
     */
    public function chargeItem()
    {
        return $this->belongsTo('App\ChargeItem','ChargeItemID', 'ChargeItemID');
    }

    public function getNameAttribute($value)
    {
        $value = (!empty($this->chargeItem)) ? $this->chargeItem->Name : null;
        return $value;
    }



}
