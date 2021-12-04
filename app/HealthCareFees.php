<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HealthCareFees extends Model
{
    protected $table        = 'dbo.HealthCareFees';
    protected $primaryKey = 'FeesID';

    /*
     * [FeesID]
      ,[FeesName]
      ,[FeesType]
      ,[FeesMinValue]
      ,[FeesMaxValue]
      ,[FeesRegYear]
      ,[CreatorID]
      ,[CreationDate]
      ,[ModifierID]
      ,[ModificationDate]
      ,[Active]
      ,[ChargeItemID]
      ,[RegistrationYearDifference]
      ,[IsEngineer]
      ,[RegistrationYearDifferenceParent]
      ,[RegistrationYearDifferenceWife]
      ,[RegistrationYearDifferenceChild]
      ,[YearCondition]
     * */
}
