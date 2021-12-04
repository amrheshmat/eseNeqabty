<?php

namespace App\Http\Controllers\Dbo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Engineer;
use App\Contact;
class BatchController extends Controller
{
  
    //Get Batch List ... 
    public function getBatchList(Request $request){ 
        $curl = curl_init();                
            $url = env('BONIAN_SERVER')."/api/ApiBatch/GetActiveLightList";
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
          // $batchList = $result['Batch']['BatchCardList'] ;

          // return view(Setting.'.DuplicatedPayment.index'); 
   }

     //Get Bending List ... 
     public function getBendingList(Request $request){ 
        $curl = curl_init();                
            $url = env('BONIAN_SERVER')."/api/ApiPaymentRequest/GetPendingPrintingPaymentRequestList?isMessageBody=true";
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
  

   //Add New Batch ...
   public function addNewBatch(Request $request){ 
    $list = $request->getContent();
    
    //return $list;
    $curl = curl_init();                
        $url = env('BONIAN_SERVER')."/api/ApiBonianBatch/Add";
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
//Add New Batch ...
public function createCorruptedBatch(Request $request){ 
    $list = $request->getContent();
    $curl = curl_init();                
        $url = env('BONIAN_SERVER')."/api/ApiBonianBatch/CreateBatchCorrupted";
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

 //Get Batch With Images
 public function getBatchWithImages(Request $request){ 
    $curl = curl_init();                
        $url = env('BONIAN_SERVER')."/api/ApiBonianBatch/GetWithImages?batchId=".$request->batchId;
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



 //Get Consolidate Batch 
 public function getConsolidateBatch(Request $request){ 
    
    
    $curl = curl_init();                
        $url = env('BONIAN_SERVER')."/api/ApiBatch/Get?id=".$request->batchId;
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
//send list of engineer that hasn't image 
public function setPendingPrinting(Request $request){ 
    $curl = curl_init();  
    $list = $request->getContent();
        $url =  env('BONIAN_SERVER')."/api/ApiPaymentRequest/SetPendingPrinting";
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
public function updateBatch(Request $request)
    {
        $curl = curl_init();
        $list = $request->getContent();
       // return $list;
        curl_setopt_array($curl, array(
        CURLOPT_URL => env('BONIAN_SERVER').'/api/ApiBonianBatchCard/UpdateCardListNFC',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_SSL_VERIFYPEER=>false,
        CURLOPT_POSTFIELDS =>$list,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
    
    
    public function updateBatchStatus(Request $request)
    {
        $curl = curl_init();
        $batchId = $request->id;
        curl_setopt_array($curl, array(
        CURLOPT_URL => env('BONIAN_SERVER').'/api/ApiBonianBatch/UpdateBatchStatus?id='.$batchId.'&status=2', // error here Conian
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
    
    public function updateBatchList(Request $request)
    {
        $curl = curl_init();
        $list = $request->getContent();
        return $list;
        curl_setopt_array($curl, array(
        CURLOPT_URL => env('BONIAN_SERVER').'/api/ApiBonianBatchCard/UpdateBatchCardList',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_SSL_VERIFYPEER=>false,
        CURLOPT_POSTFIELDS =>$list,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
