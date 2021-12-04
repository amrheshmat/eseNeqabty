<?php

namespace App\Http\Controllers\Dbo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DateTime;
use Carbon\Carbon;

class EngineeringRecordsController extends Controller
{
    public function RegistryDataValidation(Request $request)
    {
        $total_other_service_amount = 0;
        $engineer = \App\Engineer::where('OldRefID',$request->user_number)->get()->first();
        if(isset($engineer))
        {
            $regdate = new DateTime($engineer->LastRegYear);
            $today = Carbon::today();
            if($today > $regdate && $engineer->Status!=2)
            {
                // status code => 1
                $registryData = array('RegistryDataID' => NULL,'RegistryEngineerID' => NULL,'EngID' => NULL,'ContactID' => NULL,'FullName' => NULL,'LastRenewYear' => NULL,'RegistryTypeID' => NULL,'RegDataStatusID' => NULL,'IsOwner' => NULL,'BirthDate' => NULL,'Mobile' => NULL,'RegisterOffice' => NULL,);
                // $amount = $this->getAmount($request->user_number);
                // $total_other_service_amount = $amount['total_other_service_amount'];

                $registryData['status_code'] = 1;
                // $registryData['total_other_service   _amount'] = $total_other_service_amount;
                $_response = $registryData;
            }
            else{
                $registryData = \App\Engineer::where('OldRefID',$request->user_number)->get()->first();
              //  $registryData2 = \App\RegistryEngineer::where('ContactID',$registryData->ContactID)->get()->first();
            //    $_data  = \App\RegistryData::where('dbo.Engineer.OldRefID', $request->user_number)
            //    ->leftJoin('dbo.RegistryEngineer', 'dbo.RegistryEngineer.RegistryDataID', '=', 'dbo.RegistryData.RegistryDataID')
            //    ->leftJoin('dbo.Engineer', 'dbo.Engineer.EngID', '=', 'dbo.RegistryEngineer.RegistryEngineerID')
            //    ->leftJoin('dbo.Contact', 'dbo.Contact.ContactID', '=', 'dbo.Engineer.ContactID')
            //    ->leftJoin('dbo.RegistryType', 'dbo.RegistryType.RegistryTypeID', '=', 'dbo.RegistryData.RegistryTypeID')
            //    ->select('dbo.RegistryData.RegistryDataID','dbo.RegistryEngineer.RegistryEngineerID'
            //    ,'dbo.Engineer.EngID','dbo.Contact.ContactID','dbo.Contact.FullName','dbo.RegistryData.LastRenewYear','dbo.RegistryData.RegistryTypeID'
            //    ,'dbo.RegistryData.RegDataStatusID','dbo.RegistryEngineer.IsOwner','dbo.Contact.BirthDate',
            //    'dbo.Contact.Mobile','dbo.Contact.RegisterOffice'
            //    ,'dbo.RegistryType.RegistryTypeID','dbo.RegistryType.RegistryTypeName')->get()->first();
            $_data  = \App\RegistryData::where('dbo.Engineer.OldRefID', $request->user_number)
            ->leftJoin('dbo.RegistryEngineer', 'dbo.RegistryEngineer.RegistryDataID', '=', 'dbo.RegistryData.RegistryDataID')
            ->leftJoin('dbo.Engineer', 'dbo.Engineer.EngID', '=', 'dbo.RegistryEngineer.RegistryEngineerID')
            ->leftJoin('dbo.Contact', 'dbo.Contact.ContactID', '=', 'dbo.Engineer.ContactID')
            ->leftJoin('dbo.RegistryType', 'dbo.RegistryType.RegistryTypeID', '=', 'dbo.RegistryData.RegistryTypeID')
            ->select('dbo.RegistryData.RegistryDataID','dbo.RegistryEngineer.RegistryEngineerID'
            ,'dbo.Engineer.EngID','dbo.Contact.ContactID','dbo.Contact.FullName','dbo.RegistryData.LastRenewYear','dbo.RegistryData.RegistryTypeID'
            ,'dbo.RegistryData.RegDataStatusID','dbo.RegistryEngineer.IsOwner','dbo.Contact.BirthDate',
            'dbo.Contact.Mobile','dbo.Contact.RegisterOffice','dbo.RegistryType.RegistryTypeID','dbo.RegistryType.RegistryTypeName'
            )->get()->first();
                
            if(isset($registryData))
                {            
                    if(date("Y") >= $_data->LastRenewYear)
                    {
                        $_data['status_code'] = 0;
                        $_response = $_data;
                    }
                    else{
                        $registryData = array('RegistryDataID' => NULL,'RegistryEngineerID' => NULL,'EngID' => NULL,'ContactID' => NULL,'FullName' => NULL,'LastRenewYear' => NULL,'RegistryTypeID' => NULL,'RegDataStatusID' => NULL,'IsOwner' => NULL,'BirthDate' => NULL,'Mobile' => NULL,'RegisterOffice' => NULL,);
                    
                        $registryData['status_code'] = 2;
                        //$registryData['total_other_service_amount'] = 0;
                       // $_data['amr']=$registryData2->RegistryDataID;
                        $_response = $registryData;
                                
                    }
                    
                }
                else{
                    $registryData = array('RegistryDataID' => NULL,'RegistryEngineerID' => NULL,'EngID' => NULL,'ContactID' => NULL,'FullName' => NULL,'LastRenewYear' => NULL,'RegistryTypeID' => NULL,'RegDataStatusID' => NULL,'IsOwner' => NULL,'BirthDate' => NULL,'Mobile' => NULL,'RegisterOffice' => NULL,);
                    $registryData['status_code'] = 3;
                   // $registryData['total_other_service_amount'] = 0;
                    $_response = $registryData;
                }
            }
        }
        else{
            # wrong user number
            $registryData = array('RegistryDataID' => NULL,'RegistryEngineerID' => NULL,'EngID' => NULL,'ContactID' => NULL,'FullName' => NULL,'LastRenewYear' => NULL,'RegistryTypeID' => NULL,'RegDataStatusID' => NULL,'IsOwner' => NULL,'BirthDate' => NULL,'Mobile' => NULL,'RegisterOffice' => NULL,);
            $registryData['status_code'] = 4;
            $registryData['total_other_service_amount'] = 0;
            $_response = $registryData;
        }
        
       
        return response()->json($_response,200);
    }


    private function getAmount($userNumber)
    {
        $ch = curl_init();
        $headers = array(
           "content-type: application/x-www-form-urlencoded",
        );
        // curl_setopt( $ch,CURLOPT_URL, url('/')'http://api.neqabty.com/Service/inquiry/3119/'.$userNumber);
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_CUSTOMREQUEST, "GET" );
        // curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        // curl_setopt( $ch,CURLOPT_POSTFIELDS, "user_number=$request->user_number");
        return $result =json_decode(curl_exec($ch), true) ;
    }
}