<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Engineer;
use App\ServiceEngineer;
use Illuminate\Http\Request;

use App\Traits\Transactions;
use App\Traits\MembershipCharges;
use App\Traits\ConsultantCharges;
use App\Traits\ApiResponser;
use App\Traits\MedicalCharges;
use App\Traits\ServiceManagement;


class ServiceController extends Controller
{
    use Transactions, MembershipCharges, ConsultantCharges, MedicalCharges, ApiResponser,ServiceManagement;

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $allowedServices=$this->getAllowedServices();
        $result = ServiceEngineer::select('ServiceID as id', 'ServiceName as name')->whereIn('ServiceID',$allowedServices)->get();
        return $this->successResponse($result);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function serviceRequest(Request $request)
    {
        $rules = ['oldRefID' => 'required|numeric', 'serviceID' => 'required|numeric'];
        $this->validate($request, $rules);
        switch ($request->input('serviceID')) {
            case 2:
                return $this->membershipPaymentRequest($request->input('oldRefID'), $request->input('serviceID'));
            case 6:
                return $this->consultantPaymentRequest($request->input('oldRefID'),$request->input('serviceID'));
            case 5194:
                return $this->medicalPaymentRequest($request->input('oldRefID'), $request->input('serviceID'));
            default:
                return $this->otherPaymentRequest($request->input('oldRefID'), $request->input('serviceID'));
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function servicePayment(Request $request)
    {
        //requestID=serviceReqDetID
        $rules = ['oldRefID' => 'required|numeric', 'requestID' => 'required|numeric', 'referenceCode' => 'required'];
        $this->validate($request, $rules);
        $engineer = Engineer::with('contact')->where('OldRefID', $request->input('oldRefID'))->first();
        $result = $this->payCharges($engineer->ContactID, $request->input('requestID'));
        if ($result['msg'] != "success") {
            return $this->errorResponse($result['msg']);
        }

        switch($result['serviceRequestDetails']->ServiceID){
            case 2:
                $output=$this->updateSubscriptionData($engineer,$result['invoice']->ReceiptNo);
                break;
            case 6;
                $output=$this->updateConsultancyData($engineer,$result['invoice']->ReceiptNo);
                break;
            case 5194;
               $output=$this->updateMedicalData($engineer,$result['invoice'],$result['serviceRequestDetails']);
                break;
        }
       // return $this->successResponse($output);
        return $this->successResponse(['receiptNo' => $result['invoice']->ReceiptNo]);
    }

    /**
     * @param int $oldRefID
     * @param int $serviceId
     * @return mixed
     */
    public function membershipPaymentRequest($oldRefID, $serviceId)
    {

        //find engineer details for the input oldRefID
        $engineer = Contact::with('engineer')->where('OldRefID', $oldRefID)->first();
        //if no engineer found for the input oldRefID return an error
        if (empty($engineer)) {
            return $this->errorResponse(trans('messages.exists', ['attribute' => 'engineer']));
        }
        $result = ['name' => $engineer->FullName];
        $chargesTransactions = $this->getServiceRequestDetails($engineer->ContactID, $serviceId);
        if (!empty($chargesTransactions)) {
            return $this->successResponse($result + $chargesTransactions);
        }
        $charges = $this->membershipChargesInquiry($oldRefID);
        if ($charges['msg'] != "success") {
            return $this->errorResponse($charges['msg']);
        }
        //we could return the created $transactions instead of calling it again as $chargesTransactions  but it contains more data than what we need
        $transactions = $this->setMembershipTransactions($charges, $engineer, $serviceId);
        $chargesTransactions = $this->getServiceRequestDetails($engineer->ContactID, $serviceId);
        return $this->successResponse($result + $chargesTransactions);
    }

    /**
     * @param array $output
     * @param mixed $engineer
     * @param int $serviceId
     * @return mixed
     */
    public function setMembershipTransactions($output, $engineer, $serviceId)
    {
        //return $engineer;
        $charges = [
            ['ChargeItemID' => 2, 'UnitPrice' => $output['receipt']['annualCharge'], 'TotalPrice' => $output['receipt']['annualCharge']],//annualCharge
            ['ChargeItemID' => 7, 'UnitPrice' => $output['receipt']['annualPenaltyCharges'], 'TotalPrice' => $output['receipt']['annualPenaltyCharges']],// annualPenaltyCharges
            ['ChargeItemID' => 2043, 'UnitPrice' => $output['receipt']['pensionBox'], 'TotalPrice' => $output['receipt']['pensionBox']],//pensionBox
            ['ChargeItemID' => 5133, 'UnitPrice' => $output['receipt']['cardFees'], 'TotalPrice' => $output['receipt']['cardFees']],//card
            ['ChargeItemID' => 6464, 'UnitPrice' => $output['receipt']['additionalCardFees'], 'TotalPrice' => $output['receipt']['additionalCardFees']],//extra card fees
        ];
        $result = $this->setRequestChargesTransaction($engineer->ContactID, $serviceId, $output['receipt']['totalCharges'], $charges);
        return $result;
    }

    /**
     * @param int $oldRefID
     * @param int $serviceId
     * @return mixed
     */
    public function consultantPaymentRequest($oldRefID, $serviceId)
    {
        //find engineer details for the input oldRefID
        $engineer = Contact::with('engineer')->where('OldRefID', $oldRefID)->first();
        //if no engineer found for the input oldRefID return an error
        if (empty($engineer)) {
            return $this->errorResponse(trans('messages.exists', ['attribute' => 'engineer']));
        }
        //check if membership subscription is paid
        $membershipInquiry = $this->membershipChargesInquiry($oldRefID);
        if ($membershipInquiry['msg'] != trans("messages.no_charges_required")) {
            return $this->errorResponse(trans("messages.membership_payment_charges_required"));
        }
        $result = ['name' => $engineer->FullName];
        $chargesTransactions = $this->getServiceRequestDetails($engineer->ContactID, $serviceId);
        if (!empty($chargesTransactions)) {
            return $this->successResponse($result + $chargesTransactions);
        }
        $charges = $this->consultantChargesInquiry($oldRefID);
        if ($charges['msg'] != "success") {
            return $this->errorResponse($charges['msg']);
        }
        //we could return the created $transactions instead of calling it again as $chargesTransactions  but it contains more data than what we need
        $transactions = $this->setConsultantTransactions($charges, $engineer, $serviceId);
        $chargesTransactions = $this->getServiceRequestDetails($engineer->ContactID, $serviceId);
        return $this->successResponse($result + $chargesTransactions);
    }


    /**
     * @param int $oldRefID
     * @param int $serviceId
     * @return mixed
     */
    public function otherPaymentRequest($oldRefID, $serviceId){
        $allowedServices=$this->getAllowedServices();
        if(!in_array($serviceId,$allowedServices)){
            return $this->errorResponse(trans("messages.service_not_available"));
        }
        //find engineer details for the input oldRefID
        $engineer = Contact::with('engineer')->where('OldRefID', $oldRefID)->first();
        //if no engineer found for the input oldRefID return an error
        if (empty($engineer)) {
            return $this->errorResponse(trans('messages.exists', ['attribute' => 'engineer']));
        }
        //check if membership subscription is paid
        $membershipInquiry = $this->membershipChargesInquiry($oldRefID);
        if ($membershipInquiry['msg'] != trans("messages.no_charges_required")) {
            return $this->errorResponse(trans("messages.membership_payment_charges_required"));
        }
        $result = ['name' => $engineer->FullName];
        $chargesTransactions = $this->getServiceRequestDetails($engineer->ContactID, $serviceId);
        if (!empty($chargesTransactions)) {
            return $this->successResponse($result + $chargesTransactions);
        }
        $serviceEngineer = ServiceEngineer::where('ServiceID', $serviceId)->first();
        $ChargeItem = $serviceEngineer->chargeItems()->where('Active', 1)->first(['Price', 'Name']);
        $charges = [
            ['ChargeItemID' => $ChargeItem->pivot->ChargeItemID, 'UnitPrice' => $ChargeItem->Price, 'TotalPrice' => $ChargeItem->Price],
        ];
        //we could return the created $transactions instead of calling it again as $chargesTransactions  but it contains more data than what we need
        $transactions = $this->setRequestChargesTransaction($engineer->ContactID, $serviceId, $ChargeItem->Price, $charges);
        $chargesTransactions = $this->getServiceRequestDetails($engineer->ContactID, $serviceId);
        return $this->successResponse($result + $chargesTransactions);
    }

    /**
     * @param array $output
     * @param mixed $engineer
     * @param int $serviceId
     * @return mixed
     */
    public function setConsultantTransactions($output, $engineer, $serviceId)
    {
        $charges = [
            ['ChargeItemID' => 6, 'UnitPrice' => $output['receipt']['consultantCharge'], 'TotalPrice' => $output['receipt']['consultantCharge']],//annualCharge
            ['ChargeItemID' => 14, 'UnitPrice' => $output['receipt']['cardFees'], 'TotalPrice' => $output['receipt']['cardFees']],//card
            ['ChargeItemID' => 6714, 'UnitPrice' => $output['receipt']['additionalCardFees'], 'TotalPrice' => $output['receipt']['additionalCardFees']],//extra card fees
        ];
        $result = $this->setRequestChargesTransaction($engineer->ContactID, $serviceId, $output['receipt']['totalCharges'], $charges);
        return $result;
    }

    /**
     * @param int $oldRefID
     * @param int $serviceId
     * @return mixed
     */
    public function medicalPaymentRequest($oldRefID, $serviceId)
    {
        //find engineer details for the input oldRefID
        $engineer = Contact::with('engineer')->where('OldRefID', $oldRefID)->first();
        //if no engineer found for the input oldRefID return an error
        if (empty($engineer)) {
            return $this->errorResponse(trans('messages.exists', ['attribute' => 'engineer']));
        }
        //check if membership subscription is paid
        $membershipInquiry = $this->membershipChargesInquiry($oldRefID);
        if ($membershipInquiry['msg'] !=trans("messages.no_charges_required")) {
            return $this->errorResponse(trans("messages.membership_payment_charges_required"));
        }
        $result = ['name' => $engineer->FullName];
        $chargesTransactions = $this->getServiceRequestDetails($engineer->ContactID, $serviceId);
        //in case medical old receipt existed it returns -1,should not continue to avoid known issue that drops "parent registration fees"
        if ($chargesTransactions == -1) {
            return $this->errorResponse("old_receipt_existed");
        }
        if (!empty($chargesTransactions)) {
            return $this->successResponse($result + $chargesTransactions);
        }
        $charges = $this->medicalChargesInquiry($oldRefID);
        if ($charges['msg'] != "success") {
            return $this->errorResponse($charges['msg']);
        }
        // return $this->successResponse($charges);
        //we could return the created $transactions instead of calling it again as $chargesTransactions  but it contains more data than what we need
         $transactions = $this->setRequestChargesTransaction($engineer->ContactID, $serviceId, $charges['totalCharges'], $charges['receipt']);
        $chargesTransactions = $this->getServiceRequestDetails($engineer->ContactID, $serviceId);
        $this->PreChargesTransactions($engineer->ContactID,$transactions['requestID']);
        return $this->successResponse($result + $chargesTransactions);
    }

    	public function getEngineer(Request $request){
        $isEngineer = Engineer::where('OldRefID',$request->OldRefID)->first();
       if(!empty($isEngineer)){
       if($isEngineer->contact->BirthDate != null){
        $date =substr($isEngineer->contact->BirthDate,0,10);
       }else{
        $date =$isEngineer->contact->BirthDate;
       }
               $_response['OldRefID'] = $isEngineer->contact->OldRefID;
               $_response['name'] = $isEngineer->contact->FullName;
               $_response['address'] = $isEngineer->contact->Address;
               $_response['phone'] = $isEngineer->contact->Telephone;
               $_response['mobile'] = $isEngineer->contact->Mobile;
               $_response['email'] = $isEngineer->contact->Email;
               $_response['birthdate'] = $date;
               $_response['graduationyear'] = $isEngineer->GraduationYear;
               $_response['PassportNumber'] = $isEngineer->PassportNumber;
               $_response['NationalNumber'] = $isEngineer->contact->NationalNumber;
              return response()->json($_response,200);
       }else{
           return response()->json('not engineer',200);
       }

   }
}


