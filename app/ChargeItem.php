<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargeItem extends Model
{

    /*[ChargeItemID]
     ,[Price]
     ,[Name]
     ,[CostCenter]
     ,[GLAcount]
     ,[Adjustable]
     ,[Active]
     ,[CreatorID]
     ,[CreatDate]
     ,[ModifierID]
     ,[ModifyDate]*/

    protected $table = 'dbo.ChargeItem';
    protected $primaryKey = 'ChargeItemID';

    public function serviceEngineers()
    {
        return $this->belongsToMany('App\ServiceEngineer', 'dbo.SeviceChargItems', 'ChargeItemID', 'ServiceID');
    }

    /**
     * one to many relation to RequestChargesTransaction
     */
    public function requestChargesTransactions()
    {
        return $this->hasMany('App\RequestChargesTransaction','ChargeItemID', 'ChargeItemID');
    }



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];


}
