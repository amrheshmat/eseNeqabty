<?php

namespace App\Http\Controllers\Dbo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Engineer;
use App\Contact;
class BatchCardController extends Controller
{
  
    //Get Batch Card List ... 
    public function getBatchCardList(Request $request){ 
        $curl = curl_init();                
            $url = env('BONIAN_SERVER')."/api/ApiBonianBatchCard/GetList?start=" . $request->start ."&end=" .$request->end . "&searchvalue=".$request->searchvalue ."&orderby=" .  $request->orderby  ."&dir=".$request->dir;
            
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

/*
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
    */
}
