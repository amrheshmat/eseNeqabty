<?php
/**
 * Created by PhpStorm.
 * User: admin
 */
namespace App\Traits;

use App\Engineer;
use App\ServiceEngineer;
use App\PenaltyValues;
use App\AdditionalChargeItems;
use Carbon\Carbon;


trait MembershipCharges
{

    /**
     * Display a listing of the resource.
     * @param int $oldRefID
     * @return mixed
     */
    public function membershipChargesInquiry($oldRefID)
    {
        $output = [
            'msg'=>'',
            'oldRefID' => $oldRefID,
            'serviceId' => 2,
            'lastRegYear'=>0,
            'birthdate'=>0,
            'pensionYear'=>0,
            'graduationYear'=>0,
            'regDate'=>0,
            'receipt' => [
                'annualCharge' => 0,
                'annualPenaltyCharges' => 0,
                'pensionBox' => 0,
                'cardFees' => 0,
                'additionalCardFees' => 0,
                'totalCharges' => 0
            ]];
        $serviceEngineer = ServiceEngineer::where('ServiceID', 2)->first();
        $engineer = Engineer::where('OldRefID', $oldRefID)->first();
        //===============================================================================================
        //in case user is not active member
        if ($engineer->Status != 2) {
            $output['msg']= trans("messages.inactive_user");
            return $output;
        }

        // in case engineer is not alive
        if ($engineer->RegStatusID != 1 &&$engineer->RegStatusID != 3) {
            $output['msg']= trans("messages.no_charges_required");
            return $output;
        }


        //==================================================================================================

        $GraduationYear = $engineer->GraduationYear;
        $output['graduationYear']=$GraduationYear;
        $output['lastRegYear']=$engineer->LastRegYear;
        $firstChargeableYear = $engineer->LastRegYear + 1;
        $CurrentYear = (int)date('Y');
        $birthdate=$engineer->contact->BirthDate;
        if($birthdate==null){
            $output['msg']=trans("messages.birth_date_is_not_available");
            return $output;
        }
        $BirthDateYear = Carbon::createFromDate($birthdate)->year;
        // return $BirthDateYear;
        $output['birthdate'] = $BirthDateYear;
        $pensionYear = $BirthDateYear + 60;
        $output['pensionYear'] = $pensionYear;
        $RegDate = Carbon::createFromDate($engineer->RegDate)->year;
        $output['regDate']=$RegDate;
        //==================================================================================================
        //check if an engineer status is pension or not
        if($engineer->RegStatusID == 3){
            $lastChargeableYear = min([$CurrentYear, max([$GraduationYear + 15, $RegDate + 10] )]);
        }else if ($CurrentYear >= $pensionYear) {
            $lastChargeableYear = min([$CurrentYear, max([$pensionYear, $GraduationYear + 15, $RegDate + 10] )]);
        } else {
            $lastChargeableYear = $CurrentYear;
        }

        // get value and additional charge items
        $annualCharges = PenaltyValues::where('Active', 'A')->get(); //table contain value in paper
        $annualCharges = collect($annualCharges)->map(function ($annualCharges) {
            return (object)$annualCharges;
        });
        $multiplier = AdditionalChargeItems::all();// table contain one row
        $multiplier = collect($multiplier)->map(function ($AdditionalCharge) {
            return (object)$AdditionalCharge;
        });
        //=================================================================================================
        //statr for loop
        for ($i = $firstChargeableYear; $i <= $lastChargeableYear; $i++) {
            $yearDetails = [];
            $yearDetails['cardExtraFee'] = 0;
            $yearDetails['pensionBox'] = 0;
            $annualCharge = $annualCharges->where('ToYear', '>=', $i - $GraduationYear)->where('FromYear', '<=', $i - $GraduationYear)->first();
            $annualMultiplier = $multiplier->where('StartYear', '<=', $i)->first();
            //======================================================
            // static condation for ����� ������� & ������ ������� ����� �������
            if ($i > 2010 && $i != 2013 && $i != $CurrentYear) {
                $yearDetails['cardExtraFee'] = 20;

            }
            //====================================================
            $yearDetails['annualCharge'] = $annualCharge->Value;// �������� ������
            $output['receipt']['annualCharge'] += $yearDetails['annualCharge'];
            $output['receipt']['totalCharges'] += $yearDetails['annualCharge'];
            //============================================
            if (!empty($annualMultiplier)) {
                $yearDetails['pensionBox'] = $annualCharge->Value * $annualMultiplier->Value;
            }
            $output['receipt']['pensionBox'] += $yearDetails['pensionBox'] + $yearDetails['cardExtraFee'];
            $output['receipt']['totalCharges'] += $yearDetails['pensionBox']+ $yearDetails['cardExtraFee'];
            //==========================================================
            if ($i != $CurrentYear) {
                $yearDetails['annualPenaltyCharges'] = $annualCharge->PenValue; // ����� �������
                $output['receipt']['annualPenaltyCharges'] += $yearDetails['annualPenaltyCharges'];
                $output['receipt']['totalCharges'] += $yearDetails['annualPenaltyCharges'];
            }
            //=======================================================
            $output['yearDetails'][$i] = $yearDetails;
        }
        if ($output['receipt']['totalCharges'] == 0) {
            $output['msg']= trans("messages.no_charges_required");
            return $output;
        }
        //=====================================================================================================
        $chargeItems = $serviceEngineer->chargeItems()->where('Active', 1)->get();
        foreach ($chargeItems as $chargeItem) {
            if ($chargeItem->ChargeItemID == 5133) {
                $output['chargeItems'][] = ['name' => 'cardFees', 'price' => $chargeItem->Price];
                $output['receipt']['cardFees'] = $chargeItem->Price;
            } else {
                $output['chargeItems'][] = ['name' => 'additionalCardFees', 'price' => $chargeItem->Price];
                $output['receipt']['additionalCardFees'] = $chargeItem->Price;
            }
            // $output['receipt'][$chargeItem->Name] = $chargeItem->Price;
            $output['receipt']['totalCharges'] += $chargeItem->Price;
        }
        $output['msg']="success";
        return $output;

    }
}

