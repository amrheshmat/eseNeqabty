<?php
/**
 * Created by PhpStorm.
 * User: admin
 */
namespace App\Traits;

use App\Engineer;
use App\Invoice;
use App\MedRegDate;
use App\HealthCareFees;
use App\MedRegRoles;
use App\Contact;
use App\MedBeneficiary;
use App\MedBeneficiaryBackup;
use App\MedBeneficiaryYearBckup;
use App\RequestChargesTransaction;
use App\InvoiceTrack;
use App\ServiceRequestDetails;


trait MedicalCharges
{
    /*
        * 1- engineer
        * 2-wife
        * 3-son
        * 4-parent
        * */
    /**
     * Display a listing of the resource.
     *
     * @param int $oldRefID
     * @return mixed
     */
    public function medicalChargesInquiry($oldRefID)
    {
        $output = [];
        //validate current date against allowed registration periods
        if (empty($this->getRegistrationDate())) {
            $output['msg'] = trans('messages.register_not_allowed');
            return $output;
        }
        //get related data for engineer and their relatives
        $engineerData = $this->medicalBeneficiaries($oldRefID);
        //validate engineer current membership status
        if ($engineerData->engineer->Status != 2) {
            $output['msg'] = trans('messages.not_active');
            return $output;
        }
        // return $this->successResponse($engineerData);
        //check if engineer is registered at medical program
        if (empty($engineerData) || empty($engineerData->medBeneficiary)) {
            $output['msg'] = trans('messages.not_registered');
            return $output;
        }
        $output = [
            'msg' => '',
            'oldRefID' => $engineerData->OldRefID,
            'RegStatusID' => $engineerData->engineer->RegStatusID,
            'FullName' => $engineerData->FullName,
            'oldbenid' => $engineerData->medBeneficiary->oldbenid,
            'lastMedCareYear' => $engineerData->medBeneficiary->lastMedCareyear,
            'totalCharges' => 0,
            'membersCount' => 0,
            'receipt' =>
                [
                    5 => ['ChargeItemID' => 5, 'UnitPrice' => 0, 'TotalPrice' => 0, 'Quantity' => 0],//member
                    8 => ['ChargeItemID' => 8, 'UnitPrice' => 0, 'TotalPrice' => 0, 'Quantity' => 0],//administration
                    5148 => ['ChargeItemID' => 5148, 'UnitPrice' => 0, 'TotalPrice' => 0, 'Quantity' => 0],//reRegistration
                    5309 => ['ChargeItemID' => 5309, 'UnitPrice' => 0, 'TotalPrice' => 0, 'Quantity' => 0],//HealthCareCardFees
                    1030 => ['ChargeItemID' => 1030, 'value' => 0, 'UnitPrice' => 0, 'TotalPrice' => 0, 'Quantity' => 0],//son
                    1031 => ['ChargeItemID' => 1031, 'value' => 0, 'UnitPrice' => 0, 'TotalPrice' => 0, 'Quantity' => 0],//parent
                    1032 => ['ChargeItemID' => 1032, 'value' => 0, 'UnitPrice' => 0, 'TotalPrice' => 0, 'Quantity' => 0],//husband
                ],
        ];


        $engCount = ($engineerData->engineer->RegStatusID == 1 || $engineerData->engineer->RegStatusID == 3) ? 1 : 0;


        $medRegRoles = $this->getMedRegRoleDetails($engineerData->engineer->GraduationYear, $engineerData->engineer->RegStatusID);
        //in case engineer is not dead get their charges
        if ($engCount == 1) {
            $output['receipt'][$engineerData->medBeneficiary->chargeItemID]['UnitPrice'] = $medRegRoles->Value;
            $output['receipt'][$engineerData->medBeneficiary->chargeItemID]['TotalPrice'] += $medRegRoles->Value;
            $output['receipt'][$engineerData->medBeneficiary->chargeItemID]['Quantity'] += 1;
            $output['totalCharges'] += $medRegRoles->Value;
            $output['membersCount'] += $engCount;
        }

        // return $this->successResponse($medRegRoles);
        //get charges for family members
        foreach ($engineerData->medBeneficiary->medBeneficiaries as $key => $familyMember) {
            // $output['receipt'][$familyMember->chargeItemID]=0;
            foreach ($medRegRoles->medRegRolesDetails as $medicalRole) {
                if ($familyMember->RELATIONTYPE == $medicalRole->RelationTypeID) {
                    $output['receipt'][$familyMember->chargeItemID]['UnitPrice'] = $medicalRole->Value;
                    $output['receipt'][$familyMember->chargeItemID]['TotalPrice'] += $medicalRole->Value;
                    $output['receipt'][$familyMember->chargeItemID]['Quantity'] += 1;
                    $output['totalCharges'] += $medicalRole->Value;
                    $output['membersCount'] += 1;
                    break;
                }

            }
        }
        //in case no family members existed
        if ($output['membersCount'] < 1) {
            $output['msg'] = trans("messages.no_charges_required");
            return $output;
        }
        //charges for administration,reRegestration and medicalCard
        $healthCareFees = $this->getHealthCareFees($engineerData->medBeneficiary->lastMedCareyear);
        // return $this->successResponse($healthCareFees);
        foreach ($healthCareFees as $healthCareFee) {
            /* $output['receipt'][$healthCareFee->ChargeItemID] = ['FeesID' => $healthCareFee->FeesID, 'FeesType' => $healthCareFee->FeesType,
                 'FeesName' => $healthCareFee->FeesName, 'ChargeItemID' => $healthCareFee->ChargeItemID];*/

            //in case receipt is paid on every member with min/max
            if ($healthCareFee->FeesType == 3) {
                //get the minimum value comparing max charge value vs min value*number of beneficiaries "including alive or pension engineer/member"
                $charges = min($healthCareFee->FeesMaxValue, $healthCareFee->FeesMinValue * (count($engineerData->medBeneficiary->medBeneficiaries) + $engCount));
            } else {
                $charges = $healthCareFee->FeesMinValue;
            }
            $output['receipt'][$healthCareFee->ChargeItemID]['UnitPrice'] = $charges;
            $output['receipt'][$healthCareFee->ChargeItemID]['Quantity'] = 1;
            $output['receipt'][$healthCareFee->ChargeItemID]['TotalPrice'] = +$charges;
            $output['totalCharges'] += $charges;
        }
        $output['msg'] = "success";
        $output['receipt'] = array_values($output['receipt']);
        return $output;

    }

    //get engineer details by oldRefID
    public function medicalBeneficiaries($oldRefId)
    {
        $result = Contact::with(['engineer', 'medBeneficiary.medBeneficiaries'])
            ->where('OldRefID', $oldRefId)
            ->first();
        return $result;
    }

    protected function getRegistrationDate()
    {
        //enable below line to allow medical request always for testing purpose
        //  return true;
        //check the period of registration in medical service
        $CurrentDate = date('Y-m-d 00:00:00.000');
        $MedRegDate = MedRegDate::where('StartDate', '<=', $CurrentDate)
            ->Where('EndDate', '>=', $CurrentDate)->where('IsMandatory', 1)->first(); //����� ������� ������ ������
        if (empty($MedRegDate)) {
            return false;
        }
        return $MedRegDate;
    }

    protected function getHealthCareFees($lastMedCareYear)
    {
        $currentYear = date('Y');
        $engineerHealthCareFees = HealthCareFees::where([
            //get rules of current year for engineer
            ['FeesRegYear', $currentYear], ['IsEngineer', 1],
            //excluding re-subscription for parents rule "should be handled manually at ESE"
            ['ChargeItemID', '<>', 6361], ['ChargeItemID', '<>', 6728]])
            //branching query selectors
            ->where(function ($query) use ($lastMedCareYear, $currentYear) {
                //in case last paid medical care year is more than 1 year from current year
                if ($lastMedCareYear >= $currentYear - 1) {
                    //user will not be charged for subscription fees
                    $query->where('ChargeItemID', '<>', 5148);
                }
            })
            ->get();
        return $engineerHealthCareFees;
    }

    protected function  getMedRegRoleDetails($graduationYear, $regStatusId)
    {
        $CurrentYear = date('Y');
        $medRegRolesDetails = MedRegRoles::with('medRegRolesDetails')
            //validate register year is current year and the main medical beneficiary is an engineer
            ->where([['RegYear', '=', $CurrentYear], ['IsEngineer', '=', 1]])
            //advanced query conditions N.t: the anonymous function scope only accepts outer parameters through use($param1,$param2)
            ->where(function ($query) use ($regStatusId, $graduationYear) {
                //case engineer's registration status is pension/3 or dead/2 graduation year not required
                if ($regStatusId == 2 || $regStatusId == 3) {
                    $query->where('RegStatusID', $regStatusId);
                    //else engineer's registration status is alive/1 by default so calculate graduation years
                } else {
                    $query->where('GFromYear', '<=', $graduationYear)->Where('GToYear', '>=', $graduationYear);
                }
            })
            ->first();
        return $medRegRolesDetails;
    }

    /**
     * @param App/Engineer $engineer
     * @param App/Invoice $invoice
     * @param App/ServiceRequestDetails $serviceRequestDetails
     * @return mixed
     */
    protected function updateMedicalData($engineer, $invoice, $serviceRequestDetails)
    {
        //get related data for engineer and their relatives
        $engineerData = $this->medicalBeneficiaries($engineer->OldRefID);
        $engCount = ($engineer->RegStatusID == 1 || $engineer->RegStatusID == 3) ? 1 : 0;
        $membersCount = (count($engineerData->medBeneficiary->medBeneficiaries) + $engCount);

        $MedRegDate = $this->getRegistrationDate();
        $medBeneficiary = MedBeneficiary::where('ContactID', $engineer->ContactID)->first();
        //update $medBeneficiary
        $medBeneficiary->lastMedCareyear = date('Y', strtotime($MedRegDate->EndDate));
        $medBeneficiary->ModifierID = 4543;
        $medBeneficiary->ModificationDate = date('Y-m-d H:i:s');
        $medBeneficiary->save();
       // return $medBeneficiary;
        //update $medBeneficiaryBackup
        $medBeneficiaryBackup = MedBeneficiaryBackup::where([
            ['ServiceReqDetID', $serviceRequestDetails->ServiceReqDetID],
            ['BeneficiaryID', $medBeneficiary->BenID]
        ])->first();
        $medBeneficiaryBackup->LastRegyear = $medBeneficiary->lastMedCareyear;
        $medBeneficiaryBackup->ModificationDate = date('Y-m-d H:i:s');
        $medBeneficiaryBackup->ModifierID = 4543;
        $medBeneficiaryBackup->ReceiptNo=$invoice->ReceiptNo;
        $medBeneficiaryBackup->save();
        //update $medBeneficiaryYearBckup
        $medBeneficiaryYearBckup = MedBeneficiaryYearBckup::create([
            'ContactID' => $engineer->ContactID,
            'ReceiptNo' => $invoice->ReceiptNo,
            'Year' => $medBeneficiary->lastMedCareyear,
            'Count' => $membersCount,
            'CreatorID' => 4543,
            'CreationDate' => date('Y-m-d H:i:s'),
        ]);
        //update invoice
        $invoice->HealthCareReceiptNo= $invoice->ReceiptNo;
        $invoice->save();
        //create invoiceTRack
        $invoiceTrackFields=[
            'ReceiptNo' => $invoice->ReceiptNo, 'ReceiptDate' => $invoice->CreatDate,
            'ContactID' => $engineer->ContactID,
            'ServiceRequestDetId' => $serviceRequestDetails->ServiceReqDetID,
            'CreatorId' => 4543,
            'CreatorDate' => date('Y-m-d H:i:s'),
            'ModifierId'=>4543,
            'ModifierDate'=>date('Y-m-d H:i:s')
        ];

        $requestChargesTransaction = RequestChargesTransaction::where('ServiceReqDetID', $serviceRequestDetails->ServiceReqDetID)
            ->get();
        foreach ($requestChargesTransaction as $charge) {
            $invoiceTrackFields+=$this->addInvoiceTrackExtraFields($charge);

        }
        $invoiceTrack = InvoiceTrack::create($invoiceTrackFields);
        $debugData=[
            'invoice'=>$invoice,
            'invoiceTrack'=>$invoiceTrack,
            'medBeneficiary'=>$medBeneficiary,
            'medBeneficiaryBackup'=>$medBeneficiaryBackup,
            'medBeneficiaryYearBckup'=>$medBeneficiaryYearBckup
        ];
        return $debugData;
    }

    /**
     * @param APP/RequestChargesTransaction $charge
     * @return array
     */
    public function addInvoiceTrackExtraFields($charge){
        $extraFields=[];
        switch ($charge->ChargeItemID) {

            case 5148:
                $extraFields['ReRegestrationFees']=intval($charge->TotalPrice);
                break;
            case 5309:
                $extraFields['HealthCareCardFees']=intval($charge->TotalPrice);
                break;
            case 8:
                $extraFields['AdminstrationFess']= intval($charge->TotalPrice);
                break;
            case 5:
                $extraFields['MemberCount']=$charge->Quantity;
                $extraFields['MemberValue']=intval($charge->TotalPrice);
                break;
            case 1030:
                $extraFields['SonCount']=$charge->Quantity;
                $extraFields['SonValue']=intval($charge->TotalPrice);
                break;
            case 1031:
                $extraFields['ParentCount']=$charge->Quantity;
                $extraFields['ParentValue']=intval($charge->TotalPrice);
                break;
            case 1032:
                $extraFields['HusbandCount']=$charge->Quantity;
                $extraFields['HusbandValue']=intval($charge->TotalPrice);
                break;
        }
        return $extraFields;
    }


//update LastAskRenewYear at MedBeneficiary so that the request appear at admin panel
    public function PreChargesTransactions($contactID, $requestID)
    {
        $MedRegDate = $this->getRegistrationDate();
        $medBeneficiary = MedBeneficiary::where('ContactID', $contactID)->first();
        $medBeneficiaryBackup = MedBeneficiaryBackup::create([
            'BeneficiaryID' => $medBeneficiary->BenID,
            'LastRegyear' => $medBeneficiary->lastMedCareyear,
            // 'ReceiptNo'=>$receiptNo,
            'creationDate' => date('Y-m-d H:i:s'),
            'CreatorID' => 4543,
            'ServiceReqDetID' => $requestID,
            'LastAskYear' => $medBeneficiary->LastAskRenewYear,
        ]);
        $medBeneficiary->LastAskRenewYear = date('Y', strtotime($MedRegDate->EndDate));
        $medBeneficiary->ModifierID = 4543;
        $medBeneficiary->ModificationDate = date('Y-m-d H:i:s');
        $medBeneficiary->save();

    }

}

