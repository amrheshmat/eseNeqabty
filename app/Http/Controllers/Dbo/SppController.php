<?php

namespace App\Http\Controllers\Dbo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Engineer;
use App\Contact;
use \App\SppLog;
class SppController extends Controller
{
  
    
   public function getRedirect(Request $request,$path){ 
   Log::info('start get Method...');
    $url = substr($request->fullUrl(),strpos($request->fullUrl(), 'api')+3);
	Log::info('Request Url ...' . $url);
    $list = $request->getContent();
   $spp_logs =new SppLog();
	Log::info('Request content ...' . $list);
    $curl = curl_init(); 
    $url = env('SPP_SERVER') . $url;
    //return $url;
    Log::info('Request Url Tartget...' . $url);
    $access_token="" ;
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) == 'HTTP_') {
            if(str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))) == 'Access-Token'){
                $access_token = $value;
            }
           
        }
    }
    
    if(isset($access_token) && !empty($access_token) && $access_token !=""){
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => true,
            CURLOPT_SSL_VERIFYPEER=>false,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'access-token: '.$access_token
              ),
           CURLOPT_CUSTOMREQUEST => "GET",
           CURLOPT_POSTFIELDS =>$list,
        ));
    }else{
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
           CURLOPT_CUSTOMREQUEST => "GET",
           CURLOPT_POSTFIELDS =>$list,
        ));
    }
       
        $_response = curl_exec($curl);
		Log::info('Request Response ...' . $_response);
        curl_close($curl);
        $batchList = json_decode($_response,TRUE);
        return $batchList;
    
}
public function postRedirect(Request $request,$path){ 
  // return "test";
	Log::info('start Post Method...');
    $url = substr($request->fullUrl(),strpos($request->fullUrl(), 'api')+4);
    $Controllername = substr($url,0,strpos($url, '/'));
    if($Controllername == 'api' || $Controllername == 'Api'){
        $url = substr($request->fullUrl(),strpos($request->fullUrl(), 'api')+8);
        $Controllername = substr($url,0,strpos($url, '/'));
    }
	Log::info('Controller Name...' .  $Controllername);
    $url = substr($request->fullUrl(),strpos($request->fullUrl(), 'api') +3);// +3 since spp sonfig will add api word ...
	Log::info('Request Url...' . $url);
    $list = $request->getContent();
    $spp_logs =new SppLog();
    $curl = curl_init(); 
    $url = env('SPP_SERVER')  . $url;
	Log::info('Request Url Tartget...' . $url);
    $access_token="" ;
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) == 'HTTP_') {
            if(str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))) == 'Access-Token'){
                $access_token = $value;
            }
           
        }
    }
   // $spp_logs->token = $access_token;
    if( $Controllername == 'ApiAttachment'){
       // $list  = file_get_contents("UploadedImage");
        
        
        $file = $_FILES["UploadedImage"];
        //return $_FILES["UploadedImage"];
   $cFile = curl_file_create($_FILES["UploadedImage"]["tmp_name"],$_FILES["UploadedImage"]["type"],$_FILES["UploadedImage"]["name"]);
        // $filename = $file->getClientOriginalName();//name
        // $filesize = $file->getClientSize();
        $headers = array("Content-Type:multipart/form-data"); // cURL headers for file uploading
        //$postfields = array("filedata" => "$filedata", "filename" => $filename);
       // dd($postfields);
       $post = array('file'=> $cFile);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST,1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => $url,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_POST => true,
        //     CURLOPT_SAFE_UPLOAD=>true,
        //     CURLOPT_SSL_VERIFYPEER=>false,
        //     CURLOPT_HTTPHEADER => array("Content-Type:multipart/form-data"),
        //    CURLOPT_CUSTOMREQUEST => "POST",
        //    CURLOPT_POSTFIELDS =>$file
        // ));
    }else{
    if(isset($access_token) && !empty($access_token) && $access_token !=""){
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => true,
            CURLOPT_SSL_VERIFYPEER=>false,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'access-token: '.$access_token
              ),
           CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS =>$list,
        ));
    }else{
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
        }
    }
        if($list == ''){
            Log::info('Request Content ...' . $list);
        }else{
            Log::info('Request Content Not Null...' );
        }
        
        $_response = curl_exec($curl);
        curl_close($curl);
        
        Log::info('Request Response ...' . $_response);
        $result = json_decode($_response,TRUE);
        if(isset($result) && is_int($result))
        {
            $spp_logs->response = $result;
        }
        else if(isset($result['ResultType']) && is_int($result['ResultType'])){
            $spp_logs->response = $result['ResultType'];
        }
        return $result;
}

public function changePassword(Request $request)
    {
        $list = $request->getContent();
        $access_token="" ;
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                if(str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))) == 'Access-Token'){
                    $access_token = $value;
                }
               
            }
        }
        try{
       
        $spp_logs =new SppLog();
        $spp_logs->from_url = $request->fullUrl();
        $spp_logs->body = $list;
     
        $url = env('SPP_SERVER').'/api/ApiRequest/ChangePassword';
        $curl = curl_init();
      
    $spp_logs->token = $access_token;
      curl_setopt_array($curl, array(
      CURLOPT_URL =>$url ,
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
          'Content-Type: application/json',
          'access-token: '.$access_token
        ),
      ));
      $response = curl_exec($curl);
      curl_close($curl);
      $spp_logs->to_url = $url;
      $spp_logs->save();
      $response = json_decode($response,TRUE);
      if($spp_logs->save()){
        return $response;
      }else{
          return $url;
      }
      
    }catch (\Exception $e){
        return $e->getMessage();
    }
      
    }
public function putRedirect(Request $request,$path){ 
    $url = substr($request->fullUrl(),strpos($request->fullUrl(), 'api')+3);
    $list = $request->getContent();
    $spp_logs =new SppLog();
  //  $spp_logs->from_url = $request->fullUrl();
    $curl = curl_init(); 
    //$url = "192.168.10.7" . $url;
    $url = env('SPP_SERVER')  . $url;
    //$url = "https://test.boniantech.com/spp/" .  $url;
    $access_token="" ;
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) == 'HTTP_') {
            if(str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))) == 'Access-Token'){
                $access_token = $value;
            }
           
        }
    }
    
    if(isset($access_token) && !empty($access_token) && $access_token !=""){
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER=>false,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'access-token: '.$access_token
              ),
           CURLOPT_CUSTOMREQUEST => "PUT",
           CURLOPT_POSTFIELDS =>$list,
        ));
    }else{
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER=>false,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
           CURLOPT_CUSTOMREQUEST => "PUT",
           CURLOPT_POSTFIELDS =>$list,
        ));

    }
        
        $_response = curl_exec($curl);
        curl_close($curl);
       
        $batchList = json_decode($_response,TRUE);
       // $spp_logs->to_url = $url;
        if(isset($batchList['ResultType']) && is_int($batchList['ResultType'])){
            $spp_logs->response = $batchList['ResultType'];
        }else if(isset($batchList['Result']['ResultType']) && is_int($batchList['Result']['ResultType'])){
            $spp_logs->response = $batchList['Result']['ResultType'];
        }
      //  $spp_logs->save();
        return $batchList;
    
}
public function deleteRedirect(Request $request,$path){ 
    $url = substr($request->fullUrl(),strpos($request->fullUrl(), 'api')+3);
    $list = $request->getContent();
    $spp_logs =new SppLog();
  //  $spp_logs->from_url = $request->fullUrl();
    $curl = curl_init(); 
    //$url =  "192.168.10.7" . $url;
	$url = env('SPP_SERVER')  . $url;
    $access_token="" ;
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) == 'HTTP_') {
            if(str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))) == 'Access-Token'){
                $access_token = $value;
            }
           
        }
    }
    
    if(isset($access_token) && !empty($access_token) && $access_token !=""){
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER=>false,
            CURLOPT_HTTPHEADER => array(
                'access-token: '.$access_token
              ),
           CURLOPT_CUSTOMREQUEST => "DELETE",
           CURLOPT_POSTFIELDS =>$list,
        ));
    }else{
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER=>false,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
           CURLOPT_CUSTOMREQUEST => "DELETE",
           CURLOPT_POSTFIELDS =>$list,
        ));
    }
        
        $_response = curl_exec($curl);
        curl_close($curl);
       
        $batchList = json_decode($_response,TRUE);
       // $spp_logs->to_url = $url;
        if(isset($batchList['ResultType']) && is_int($batchList['ResultType'])){
            $spp_logs->response = $batchList['ResultType'];
        }else if(isset($batchList['Result']['ResultType']) && is_int($batchList['Result']['ResultType'])){
            $spp_logs->response = $batchList['Result']['ResultType'];
        }
        //$spp_logs->save();
        return $batchList;
    
}
   
  

}
