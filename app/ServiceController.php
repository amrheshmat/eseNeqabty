<?php

namespace App\Http\Controllers;

use App\ChargeItemsKey;
use App\Contact;
use App\ServiceEngineer;
use Illuminate\Http\Request;

use App\Traits\Transactions;
use App\Traits\MembershipCharges;
use App\Traits\ConsultantCharges;
use App\Traits\ApiResponser;
use App\Traits\MedicalCharges;

class ServiceController extends Controller
{
    use Transactions, MembershipCharges,ConsultantCharges,MedicalCharges, ApiResponser;

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $result=ServiceEngineer::select('ServiceID as id', 'ServiceName as name')->whereIn('ServiceID', [2,6,5194])->get();
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
                return $this->membershipPaymentRequest($request,2);
            case 6:
                return $this->consultantPaymentRequest($request,6);
            case 5194:
                return $this->medicalPaymentRequest($request,5194);
        }
        return $this->errorResponse(trans('validation.exists', ['attribute' => 'service']));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function servicePayment(Request $request)
    {
        $rules = ['serviceID' => 'required|numeric','requestID' => 'required|numeric', 'receipt' => 'required'];
        $this->validate($request, $rules);
        return $this->successResponse(['result'=>'success']);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function membershipPaymentRequest(Request $request,$serviceId)
    {

        //find engineer details for the input oldRefID
        $engineer = Contact::with('engineer')->where('OldRefID', $request->input('oldRefID'))->first();
        //if no engineer found for the input oldRefID return an error
        if (empty($engineer)) {
            return $this->errorResponse(trans('validation.exists', ['attribute' => 'engineer']));
        }
        $result = ['name' => $engineer->FullName];
        $chargesTransactions = $this->getServiceRequestDetails($engineer->ContactID, $serviceId);
        if (!empty($chargesTransactions)) {
            return $this->successResponse($result + $chargesTransactions);
        }
        $charges = $this->membershipChargesInquiry($request->input('oldRefID'));
        if ($charges['msg'] != "success") {
            return $this->errorResponse($charges['msg']);
        }
        //we could return the created $transactions instead of calling it again as $chargesTransactions  but it contains more data than what we need
        $transactions = $this->setMembershipTransactions($charges, $engineer,$serviceId);
        $chargesTransactions = $this->getServiceRequestDetails($engineer->ContactID, $serviceId);
        return $this->successResponse($result + $chargesTransactions);
    }

    /**
     * @param array $output
     * @param mixed $engineer
     * @param int $serviceId
     * @return mixed
     */
    public function setMembershipTransactions($output, $engineer,$serviceId)
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
     * @param Request $request
     * @return mixed
     */
    public function membershipPayment(Request $request)
    {
      /* $rules = ['oldRefID' => 'required|numeric'];
        $this->validate($request, $rules);
        $engineer = Contact::with('engineer')->where('OldRefID', $request->input('oldRefID'))->first();
        $transactions = $this->getServiceRequestDetails($engineer->ContactID, 2);
        $output = $this->membershipCharges($request->input('oldRefID'));
        if ($output['msg'] != "success") {
            return $this->errorResponse($output['msg']);
        }
        $result = $this->membershipTransactions($output, $engineer);
        return $this->successResponse($result);*/
    }
    /*
     SELECT ChargeItem.* from ServiceEngineer join SeviceChargItems on  ServiceEngineer.ServiceID=SeviceChargItems.ServiceID  join ChargeItem on SeviceChargItems.ChargeItemID=ChargeItem.ChargeItemID
    where ServiceEngineer.ServiceID=6;
     */
    /**
     * @param Request $request
     *  @param int $serviceId
     * @return mixed
     */
    public function consultantPaymentRequest(Request $request,$serviceId)
    {
        //find engineer details for the input oldRefID
        $engineer = Contact::with('engineer')->where('OldRefID', $request->input('oldRefID'))->first();
        //if no engineer found for the input oldRefID return an error
        if (empty($engineer)) {
            return $this->errorResponse(trans('validation.exists', ['attribute' => 'engineer']));
        }
        //check if membership subscription is paid
        $membershipInquiry = $this->membershipChargesInquiry($request->input('oldRefID'));
        if ($membershipInquiry['msg'] != "no_charges_required") {
            return $this->errorResponse("membership_payment_charges_required");
        }
        $result = ['name' => $engineer->FullName];
        $chargesTransactions = $this->getServiceRequestDetails($engineer->ContactID, $serviceId);
        if (!empty($chargesTransactions)) {
            return $this->successResponse($result + $chargesTransactions);
        }
        $charges = $this->consultantChargesInquiry($request->input('oldRefID'));
        if ($charges['msg'] != "success") {
            return $this->errorResponse($charges['msg']);
        }
        //we could return the created $transactions instead of calling it again as $chargesTransactions  but it contains more data than what we need
        $transactions = $this->setConsultantTransactions($charges, $engineer,$serviceId);

        $chargesTransactions = $this->getServiceRequestDetails($engineer->ContactID, $serviceId);
        return $this->successResponse($chargesTransactions);
        return $this->successResponse($result + $chargesTransactions);
    }

    /**
     * @param array $output
     * @param mixed $engineer
     * @param int $serviceId
     * @return mixed
     */
    public function setConsultantTransactions($output, $engineer,$serviceId)
    {
        $charges = [
            ['ChargeItemID' => 6, 'UnitPrice' => $output['receipt']['consultantCharge'], 'TotalPrice' => $output['receipt']['consultantCharge']],//annualCharge
            ['ChargeItemID' => 14, 'UnitPrice' => $output['receipt']['cardFees'], 'TotalPrice' => $output['receipt']['cardFees']],//card
            ['ChargeItemID' => 6714, 'UnitPrice' => $output['receipt']['additionalCardFees'], 'TotalPrice' => $output['receipt']['additionalCardFees']],//extra card fees
        ];
        $result = $this->setRequestChargesTransaction($engineer->ContactID, $serviceId, $output['receipt']['totalCharges'], $charges);
        return $result;
    }



    // تجديد اشتراك استشاري Closed



   /* public function consultantPayment(Request $request)
    {
        $rules = ['oldRefID' => 'required|numeric'];
        $this->validate($request, $rules);
        $engineer = Contact::with('engineer')->where('OldRefID', $request->input('oldRefID'))->first();
        $transactions = $this->findOldRequest($engineer->ContactID, 6);
        $output = $this->consultantInquiry($request);
        if ($output['msg'] != "success") {
            // return $this->errorResponse($output['msg']);
            $output['msg'] = "amr";
            return $output;
        }
        $result = $this->consultantChargesTransactions($output, $engineer);
        return $this->successResponse($result);
    }*/

    /**
     * @param Request $request
     *  @param int $serviceId
     * @return mixed
     */
    public function medicalPaymentRequest(Request $request,$serviceId)
    {
        //find engineer details for the input oldRefID
        $engineer = Contact::with('engineer')->where('OldRefID', $request->input('oldRefID'))->first();
        //if no engineer found for the input oldRefID return an error
        if (empty($engineer)) {
            return $this->errorResponse(trans('validation.exists', ['attribute' => 'engineer']));
        }
        //check if membership subscription is paid
        $membershipInquiry = $this->membershipChargesInquiry($request->input('oldRefID'));
        if ($membershipInquiry['msg'] != "no_charges_required") {
            return $this->errorResponse("membership_payment_charges_required");
        }
        $result = ['name' => $engineer->FullName];
        $chargesTransactions = $this->getServiceRequestDetails($engineer->ContactID, $serviceId);
        if (!empty($chargesTransactions)) {
            return $this->successResponse($result + $chargesTransactions);
        }
        $charges = $this->medicalChargesInquiry($request->input('oldRefID'));
        if ($charges['msg'] != "success") {
            return $this->errorResponse($charges['msg']);
        }
        //we could return the created $transactions instead of calling it again as $chargesTransactions  but it contains more data than what we need
        $transactions = $this->setMedicalTransactions($charges, $engineer,$serviceId);
        $chargesTransactions = $this->getServiceRequestDetails($engineer->ContactID, $serviceId);
        return $this->successResponse($result + $chargesTransactions);
    }

    /**
     * @param array $output
     * @param mixed $engineer
     * @param int $serviceId
     * @return mixed
     */
    public function setMedicalTransactions($output, $engineer,$serviceId)
    {
        $charges= [
            ['ChargeItemID' => 5, 'UnitPrice' => $output['receipt'][5], 'TotalPrice' => $output['receipt'][5]],
            ['ChargeItemID' => 8, 'UnitPrice' => $output['receipt'][8], 'TotalPrice' => $output['receipt'][8]],
            ['ChargeItemID' => 5148, 'UnitPrice' => $output['receipt'][5148], 'TotalPrice' => $output['receipt'][5148]],
          //  ['ChargeItemID' => 3509, 'UnitPrice' => $output['receipt'][3509], 'TotalPrice' => $output['receipt'][3509]],
            ['ChargeItemID' => 1030, 'UnitPrice' => $output['receipt'][1030], 'TotalPrice' => $output['receipt'][1030]],
            ['ChargeItemID' => 1031, 'UnitPrice' => $output['receipt'][1031], 'TotalPrice' => $output['receipt'][1031]],
            ['ChargeItemID' => 1032, 'UnitPrice' => $output['receipt'][1032], 'TotalPrice' => $output['receipt'][1032]],
        ];
        $result = $this->setRequestChargesTransaction($engineer->ContactID, $serviceId, $output['receipt']['totalCharges'], $charges);
        return $result;
    }

}


