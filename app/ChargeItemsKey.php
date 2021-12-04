<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargeItemsKey extends Model
{
    protected $table        = 'dbo.ChargeItemsKey';
    protected $primaryKey = 'ChargeKey';
    public $timestamps = false;

/*
[ChargeKey]
      ,[ChargeItemID]
      ,[Description]
*/

    public function medBeneficiary()
    {
        return $this->belongsTo('App\MedBeneficiary', 'chargeItemID', 'ChargeItemID');
    }
}
