<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceEngineer extends Model
{
    protected $table        = 'dbo.ServiceEngineer';
    protected $primaryKey = 'ServiceID';
   public function chargeItems(){
        return $this->belongsToMany('App\ChargeItem','dbo.SeviceChargItems','ServiceID','ChargeItemID');
    }
}
