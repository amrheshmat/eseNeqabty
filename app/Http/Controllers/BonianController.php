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

use Illuminate\Support\Facades\Input;

class BonianController extends Controller
{
    public function GetFollowersList()
    {
       $oldRefId = Input::get('oldRefId');
       $curl = curl_init();
       curl_setopt_array($curl, array(
       CURLOPT_URL => env('BONIAN_SERVER').'/api/ApiHealthCare/GetFollowersList?oldRefId='.$oldRefId,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => 'GET',
       ));
       $response = curl_exec($curl);
       curl_close($curl);
       return $response;
    }
    public function Register(Request $request)
    {
       $url = env('BONIAN_SERVER').'/api/ApiHealthCare/Register';
       $list = $request->getContent();
       $curl = curl_init();
       curl_setopt_array($curl, array(
       CURLOPT_URL => $url,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => "POST",
       CURLOPT_POSTFIELDS =>$list,
       CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
       ));
       $response = curl_exec($curl);
       curl_close($curl);
       return $response;
    }
    
    public function MedBeneficiaryFollowersUpdate(Request $request)
    {
       $raw = file_get_contents("php://input");
       $curl = curl_init();
       curl_setopt_array($curl, array(
       CURLOPT_URL => env('BONIAN_SERVER').'/api/ApiHealthCare/MedBeneficiaryFollowersUpdate',
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => 'POST',
       CURLOPT_POSTFIELDS =>$raw,
       CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
       ));
       $response = curl_exec($curl);
       curl_close($curl);
       return $response;
    }
    
    public function AddHealthCareRequest(Request $request)
    {
      $oldRefId = Input::get('oldRefId');
      $deliveryLocation = urlencode(Input::get('deliveryLocation'));
      $deliveryAddress = urlencode(Input::get('deliveryAddress'));
      $deliveryPhone = Input::get('deliveryPhone'); 
      
      $url = env('BONIAN_SERVER').'/api/apiPaymentRequest/AddHealthCareRequest?oldRefId='.$oldRefId.'&deliveryLocation='.$deliveryLocation.'&deliveryAddress='.$deliveryAddress.'&deliveryPhone='.$deliveryPhone;
      
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array('Content-Length: 0'),
      ));
      $response = curl_exec($curl);
      curl_close($curl);
      return $response;
    }
    
    public function CancelHealthCareRequest(Request $request)
    {
       $oldRefId = Input::get('oldRefId');
      
       $url = env('BONIAN_SERVER').'/api/apiPaymentRequest/CancelHealthCareRequest?oldRefId='.$oldRefId;
      
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array('Content-Length: 0'),
      ));
      $response = curl_exec($curl);
      curl_close($curl);
      return $response;
    }
    
    public function SetPaid(Request $request)
    {
       $paymentRequestNumber = Input::get('paymentRequestNumber');
       $status = Input::get('status');
      
       $url = env('BONIAN_SERVER').'/api/apiPaymentRequest/SetPaid?paymentRequestNumber='.$paymentRequestNumber.'&status='.$status;
       \Log::debug('SetPaid URL: '.$url);
       $curl = curl_init();
       curl_setopt_array($curl, array(
         CURLOPT_URL => $url,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_HTTPHEADER => array('Content-Length: 0'),
        ));
        $response = curl_exec($curl);
        curl_close($curl);return $response;
    }
    
    
    public function AddRenewalRequest(Request $request)
    {
       $oldrefid = Input::get('oldrefid');
      
       $url = env('BONIAN_SERVER').'/api/apiPaymentRequest/AddRenewalRequest?oldrefid='.$oldrefid;
      
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array('Content-Length: 0'),
      ));
      $response = curl_exec($curl);
      curl_close($curl);
      return $response;
    }

    public function InquiryProcess()
    {
        $oldRefId = Input::get('oldRefId');
        $paymentType = Input::get('paymentType');
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => env('BONIAN_SERVER').'/api/apiPaymentRequest/InquiryProcess?oldRefId='.$oldRefId.'&paymentType='.$paymentType,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    
    public function HealthCareInquiryDetails()
    {
        $oldRefId = Input::get('OldrefID');
        $paymentType = Input::get('paymentType');
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => env('BONIAN_SERVER').'/api/apiPaymentRequest/HealthCareInquiryDetails?OldrefID='.$oldRefId,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }


    public function RenewalInquiryDetails()
    {
        $oldRefId = Input::get('OldrefID');
        $paymentType = Input::get('paymentType');
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => env('BONIAN_SERVER').'/api/apiPaymentRequest/RenewalInquiryDetails?OldrefID='.$oldRefId,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}


