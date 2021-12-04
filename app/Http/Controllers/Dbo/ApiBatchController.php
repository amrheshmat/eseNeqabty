<?php

namespace App\Http\Controllers\Dbo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Engineer;
use App\Contact;
class ApiBatchController extends Controller
{

    public function GetLast(Request $request){ 
        $curl = curl_init();                
            $url = env('BONIAN_SERVER')."/api/ApiBonianBatch/GetLast";
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_POST => true,
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_HTTPHEADER => array('Content-Length: 0'),
               CURLOPT_CUSTOMREQUEST => "GET",
            ));
            $_response = curl_exec($curl);
            
           
            $batchList = json_decode($_response,TRUE);
            return $batchList;

   }
   ///////////////////////////////////////////////////////////////////
   public function GetNewBatchList(Request $request){ 
    $curl = curl_init();                
        $url = env('BONIAN_SERVER')."/api/ApiBonianBatch/GetNewBatchList";
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => true,
            CURLOPT_SSL_VERIFYPEER=>false,
            CURLOPT_HTTPHEADER => array('Content-Length: 0'),
           CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $_response = curl_exec($curl);
        
       
        $batchList = json_decode($_response,TRUE);
        return $batchList;

}
///////////////////////////////////////////////////////////////////////
public function Get(Request $request){ 
    $curl = curl_init();                
        $url = env('BONIAN_SERVER')."/api/ApiBonianBatch/Get?id=".$request->id;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => true,
            CURLOPT_SSL_VERIFYPEER=>false,
            CURLOPT_HTTPHEADER => array('Content-Length: 0'),
           CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $_response = curl_exec($curl);
        
       
        $batchList = json_decode($_response,TRUE);
        return $batchList;

}
//////////////////////////////////////////////////////////////

    public function updateBatchStatus(Request $request)
    {
        $curl = curl_init();
        $batchId = $request->id;
        $status = $request->status;
        curl_setopt_array($curl, array(
        CURLOPT_URL => env('BONIAN_SERVER').'/api/ApiBonianBatch/UpdateBatchStatus?id='.$batchId.'&status='.$status, // error here Conian
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_SSL_VERIFYPEER=>false,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function UpdateBatchCardStatus(Request $request)
    {
        $curl = curl_init();
        $batchId = $request->id;
        $status = $request->status;
        curl_setopt_array($curl, array(
        CURLOPT_URL => env('BONIAN_SERVER').'/api/ApiBonianBatchCard/UpdateBatchCardStatus?id='.$batchId.'&status='.$status, // error here Conian
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_SSL_VERIFYPEER=>false,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    /////////////////////////////////////////////////////////////////////////////
    public function UpdateCardListStatus(Request $request){ 
        $list = $request->getContent();
        $curl = curl_init();                
            $url = env('BONIAN_SERVER')."/api/ApiBonianBatchCard/UpdateCardListStatus";
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_POST => true,
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS =>$list,
            ));
            $_response = curl_exec($curl);
            
           
            $batchList = json_decode($_response,TRUE);
            return $batchList;
    }
}