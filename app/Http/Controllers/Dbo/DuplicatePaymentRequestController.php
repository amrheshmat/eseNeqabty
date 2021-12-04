<?php

namespace App\Http\Controllers\Dbo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Engineer;
use App\Contact;
class DuplicatePaymentRequestController extends Controller
{
  
    //Get duplicated Payment Request ... 
    public function duplicateRequest(Request $request){ 
        $curl = curl_init();                
            $url = env('BONIAN_SERVER')."/api/apiPaymentRequest/GetDuplicatedEngineerPR?oldrefId=&Start=-1&End=0&orderby=&dir=&PaymentRequestNumber=";
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => array('Content-Length: 0'),
               CURLOPT_CUSTOMREQUEST => "GET",
            ));
            $_response = curl_exec($curl);
            
           
            $batchList = json_decode($_response,TRUE);
            return $batchList;
          // $batchList = $result['Batch']['BatchCardList'] ;

          // return view(Setting.'.DuplicatedPayment.index'); 
   }
  
   
}
