<?php
/**
 * Created by PhpStorm.
 * User: admin
 */
namespace App\Traits;

use App\Engineer;
use App\ServiceEngineer;
use App\ConsultantRegistrationValues;


trait ConsultantCharges
{
    /**
     * Display a listing of the resource.
     * @param int $oldRefID
     * @return mixed
     */
    public function consultantChargesInquiry($oldRefID)
    {
        // check if Engineer is dead or pension or Etc....
        $serviceEngineer = ServiceEngineer::where('ServiceID', 6)->first();
        $engineer = Engineer::where('OldRefID', $oldRefID)->first();
        $output = [
            'msg' => '',
            'oldRefID' => $engineer->OldRefID,
            'serviceId' => 6,
            'lastRegYear' => 0,
            'contactID' => $engineer->ContactID,
            'birthdate' => 0,
            'pensionYear' => 0,
            'graduationYear' => 0,
            'regDate' => 0,
            'deletePreviousRequest' => '',
            'receipt' => [
                'consultantCharge' => 0,
                'cardFees' => 0,
                'additionalCardFees' => 0,
                'totalCharges' => 0
            ]];

        // in case engineer is not alive
        if ($engineer->RegStatusID != 1 && $engineer->RegStatusID != 3) {
            $output['msg'] = trans("messages.inactive_consultant");
            return $output;
        }
        // in case engineer is not consultant
        if ($engineer->ConsultID == null) {
            $output['msg'] = trans("messages.not_consultant");
            return $output;
        }
        //in case user is not active member
        if ($engineer->Status != 2) {
            $output['msg'] = trans("messages.inactive_user");
            return $output;
        }

        $CurrentYear = (int)date('Y');
        $LastConsYear = $engineer->LastConsYear;
        $output['lastRegYear'] = $LastConsYear;
        if ($CurrentYear == $LastConsYear) {
            $output['msg'] = trans("messages.no_charges_required");
            return $output;
        }
        $unpaidYear = $LastConsYear + 1;
        //get yearly payment values from ConsultantRegistrationValues
        $ConsultantRegistrationTotal = 0;
        $ConsultantRegistrationObject = ConsultantRegistrationValues::where('Active', 1)->get();
        for ($i = $unpaidYear; $i <= $CurrentYear; $i++) {
            $ConsultantRegistrationValues = $ConsultantRegistrationObject->where('ToYear', '>=', $i)->where('FromYear', '<=', $i)->first();
            $output['receipt']['consultantCharge'] += $ConsultantRegistrationValues->Value;
            $output['receipt']['totalCharges'] += $ConsultantRegistrationValues->Value;
        }
        $ChargeItemsPrice = 0;
        $additionalValue = 0;
        //get charge items where isActive =1
        $ChargeItems = $serviceEngineer->chargeItems()->where('Active', 1)->get(['Price', 'Name']);
        foreach ($ChargeItems as $key => $value) {
            if (!defined('printValue')) define('printValue', $value->Price);
            $ChargeItemsPrice += $value->Price;
            $output['receipt']['cardFees'] = printValue;
            $additionalValue = $ChargeItemsPrice - printValue;
            $output['receipt']['additionalCardFees'] = $additionalValue;
        }

        $output['receipt']['totalCharges'] += $ChargeItemsPrice;
        $output['msg'] = "success";
        return $output;
    }
}

