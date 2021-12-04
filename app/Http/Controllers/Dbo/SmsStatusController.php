<?php

namespace App\Http\Controllers\Dbo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Engineer;
use App\Contact;
class SmsStatusController extends Controller
{
  
    //Get duplicated Payment Request ... 
    public function setPendingPrintingSmsStatus(Request $request){ 
        $curl = curl_init();                
        $list = $request->getContent();
        $url =  env('BONIAN_SERVER')."/api/ApiPaymentRequest/SetCorruptionSMS";
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => true,
            CURLOPT_SSL_VERIFYPEER=>false,
            CURLOPT_HTTPHEADER => array('Content-Length: 0'),
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>$list,
        ));
        $_response = curl_exec($curl);
        $batchList = json_decode($_response,TRUE);
        return $batchList;  
   }
  
   
}
