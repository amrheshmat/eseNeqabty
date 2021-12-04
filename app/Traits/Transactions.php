<?php
/**
 * Created by PhpStorm.
 * User: admin
 */
namespace App\Traits;

use App\ChargeItem;
use App\Engineer;
use App\ServiceRequest;
use App\ServiceRequestDetails;
use App\RequestChargesTransaction;
use App\Invoice;
use App\EngineerBackup;



trait Transactions
{
    /**
     * Check if the input contactId already has an active request charges transactions
     *
     * @param  int $contactId
     * @param int $serviceId
     * @return mixed
     */
    public function getServiceRequestDetails($contactId, $serviceId)
    {
        $serviceRequestDetails=$users =ServiceRequestDetails::whereIn('ServiceReqDetID',function($query) use($serviceId,$contactId) {
           $query ->select('ServiceRequestDetails.ServiceReqDetID')
            ->from('ServiceRequest')
                ->join('ServiceRequestDetails', 'ServiceRequestDetails.RequestID', '=', 'ServiceRequest.RequestID')
                ->where([
                    ['ServiceRequest.ContactID', '=', $contactId],
                    ['ServiceRequestDetails.RequestStatus', '=', 11],
                    ['ServiceRequestDetails.ServiceID', '=', $serviceId]])->get();
        })->orderBy('ServiceRequestDetails.CreatDate', 'desc')
        ->first();

        if (empty($serviceRequestDetails)) {
            return false;
        }

       // if returned request details older than today cancels it and return false
        if (date('Y-m-d')> date('Y-m-d', strtotime($serviceRequestDetails->CreatDate))) {
            //skip canceling old medical request to avoid known issue dropping re-registered parents fees.
            if($serviceId==5194){
                return -1;
            }
            $this->cancelRequest($serviceRequestDetails);
            //return $serviceRequestDetails;
            return false;
        }
        $result = ['requestID'=>$serviceRequestDetails->ServiceReqDetID,'total'=>0];
        $result['details']=$this->getRequestChargesTransaction($serviceRequestDetails->ServiceReqDetID);
        foreach ($result['details'] as $charge){
            $result['total']+=$charge->TotalPrice;
        }
        //return charges transactions for the valid request service
        return $result;
    }

    /**
     * get charges for the $serviceReqDetID
     * @param  int $serviceReqDetID
     * @return RequestChargesTransaction
     */
    public function getRequestChargesTransaction($serviceReqDetID){
        $requestChargesTransaction = RequestChargesTransaction::with('chargeItem')
            ->where('ServiceReqDetID', $serviceReqDetID)
            ->get();
        return $requestChargesTransaction;
    }

    /**
     * cancel $serviceRequestDetails
     * @param  ServiceRequestDetails $serviceRequestDetails
     */
    public function cancelRequest($serviceRequestDetails)
    {
        $serviceRequestDetails->RequestStatus = 3;
        $serviceRequestDetails->CanceledReason = 'old transaction';
        $serviceRequestDetails->CanceledDate = date('Y-m-d H:i:s');
        $serviceRequestDetails->CanceledUser = 4543;
        $serviceRequestDetails->save();
    }


    /**
     * @param int $contactId
     * @param int $serviceId
     * @param int $totalPrice
     * @param array $charges
     * @return mixed
     */
    public function setRequestChargesTransaction($contactId, $serviceId, $totalPrice, $charges)
    {
        $result = ['requestID'=>0,'charges'=>[]];
        $serviceRequest = ServiceRequest::create(['ContactID' => $contactId]);
        $serviceRequestDetails = ServiceRequestDetails::create([
            'RequestID' => $serviceRequest->RequestID,
            'ServiceID' => $serviceId,
            'RequestStatus' => 11,
            'TotalPrice' => $totalPrice,
            'CreatorID'=>4543,
            'CreatDate' => date('Y-m-d H:i:s')
        ]);
        foreach ($charges as $charge) {
            $requestChargesTransaction = $charge + [
                    'ServiceReqDetID' => $serviceRequestDetails->ServiceReqDetID,
                    'CreatDate' => date('Y-m-d H:i:s')
                ];
            $row = RequestChargesTransaction::create($requestChargesTransaction);
            $result['charges'][] = $row;
        }
        $result['requestID']=$serviceRequestDetails->ServiceReqDetID;
        return $result;
    }




    /**
     * @param int $contactID
     * @param int $serviceReqDetID
     * @return int
     */
    public function payCharges($contactID, $serviceReqDetID)
    {
        $serviceRequestDetails = ServiceRequestDetails::with('serviceRequest')
        ->where([
            ['ServiceReqDetID',$serviceReqDetID],
           // ['RequestStatus',11],
        ])
        ->first();
        if(empty($serviceRequestDetails)||$serviceRequestDetails->serviceRequest->ContactID!=$contactID){
           return ['msg'=>trans('message.receipt_expired')];
        }
        $serviceRequestDetails->RequestStatus = 10;
        $serviceRequestDetails->ModifierID = 4543;
        $serviceRequestDetails->ModifyDate = date('Y-m-d H:i:s');

        $invoice = Invoice::create([
            'Total' => $serviceRequestDetails->TotalPrice,
            'Date' => date('Y-m-d H:i:s'),
            'CreatDate' => date('Y-m-d H:i:s'),
            'CreatorID' => 4543,
            'ContactID' => $contactID,
        ]);
        $invoice->ReceiptNo = $invoice->InvoiceID;
        $invoice->save();

        $serviceRequestDetails->InvoiceID = $invoice->InvoiceID;
        $serviceRequestDetails->save();
       $result=['msg'=>"success",
           'invoice'=>$invoice,
           'serviceRequestDetails'=>$serviceRequestDetails];
        return $result;
    }

    /**
     * @param App/Engineer $engineer
     * @param int $receiptNo
     * @return array
     */
    protected function updateSubscriptionData($engineer,$receiptNo){
        $engineerBackup = EngineerBackup::create([
            'EngID' => $engineer->EngID,
            'LastRegDate' => $engineer->LastRegDate,
            'LastRegYear' => $engineer->LastRegYear,
            'LastConsYear' => $engineer->LastConsYear,
            'LastConsDate' => $engineer->LastConsDate,
            'IsDoctor' => $engineer->IsDoctor,
            'EducationLevelID' => $engineer->EducationLevelID,
            'ConsultantRegDate' => $engineer->ConsultantRegDate,
            'ContactType' => $engineer->contact->ContactTypeID,
            'ContactGroup' => $engineer->contact->ContactGroupID,
            'ReceiptNo' => $receiptNo,
            'Status' => $engineer->Status,
            'CreationDate' => date('Y-m-d H:i:s'),
            'CreatorID' => 4543,
            'CurrentRegYear' => date('Y'),//update current registration year
        ]);
        $engineer->LastRegYear = $engineerBackup->CurrentRegYear;
        $engineer->LastRegDate = $engineerBackup->CreationDate;
        $engineer->save();
        $debugData=['engineerBackup'=>$engineerBackup,'engineer'=>$engineer];
        return $debugData;
    }
    /**
     * @param App/Engineer $engineer
     * @param int $receiptNo
     * @return array
     */
    protected function updateConsultancyData($engineer,$receiptNo){
        $engineerBackup = EngineerBackup::create([
            'EngID' => $engineer->EngID,
            'LastRegDate' => $engineer->LastRegDate,
            'LastRegYear' => $engineer->LastRegYear,
            'LastConsYear' => $engineer->LastConsYear,
            'LastConsDate' => $engineer->LastConsDate,
            'IsDoctor' => $engineer->IsDoctor,
            'EducationLevelID' => $engineer->EducationLevelID,
            'ConsultantRegDate' => $engineer->ConsultantRegDate,
            'ContactType' => $engineer->contact->ContactTypeID,
            'ContactGroup' => $engineer->contact->ContactGroupID,
            'ReceiptNo' => $receiptNo,
            'Status' => $engineer->Status,
            'CreationDate' => date('Y-m-d H:i:s'),
            'CreatorID' => 4543,
            'CurrentConsYear' => date('Y'),//update current consultancy year
        ]);
        $engineer->LastConsYear = $engineerBackup->LastConsYear;
        $engineer->LastConsDate = $engineerBackup->LastConsDate;
        $engineer->save();
        $debugData=['engineerBackup'=>$engineerBackup,'engineer'=>$engineer];
        return $debugData;
    }
}
