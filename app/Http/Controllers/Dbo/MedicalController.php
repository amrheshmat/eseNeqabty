<?php

namespace App\Http\Controllers\Dbo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DboServiceProvider;
use App\DboContact;
use App\Contact;

use DateTime;
use Carbon\Carbon;

class MedicalController extends Controller
{
    public function pullProviders()
    {
        $service_providers = DboServiceProvider::get(['ServiceProviderID','ServiceProviderName','ServiceProviderTypeId','Address',
        'TelephoneNumber','GovernorateId','PoliceStationId','Email','MedicalSpecialityId']);
        // foreach ($service_providers as $provider) 
        // {
        //     $medicalProvider = new MedicalProvider();
        //     $medicalProvider->provider_id        = $provider->ServiceProviderID;
        //     $medicalProvider->name               = $provider->ServiceProviderName;
        //     $medicalProvider->provider_type_id   = $provider->ServiceProviderTypeId;
        //     $medicalProvider->address            = $provider->Address == NULL ?  " " : $provider->Address;
        //     $medicalProvider->phones             = $provider->TelephoneNumber;
        //     $medicalProvider->governorate_id     = $provider->GovernorateId;
        //     $medicalProvider->area_id            = $provider->PoliceStationId;
        //     $medicalProvider->emails             = $provider->Email;
        //     $medicalProvider->profession_id      = 0;
        //     $medicalProvider->degree_id          = 0;
        //     $medicalProvider->education_level_id = 0;
        //     $medicalProvider->save();
        // }
        return response()->json($service_providers,200);
    }

  public function engineerMedicalData(Request $request)
{
    $rules = ['oldRefID' => 'required|numeric'];
    $this->validate($request, $rules);
    $engineerMedicalData= $result = Contact::with(['engineer', 'medBeneficiary.medBeneficiaries'])
        ->where('OldRefID', $request->input('oldRefID'))
        ->first();
    return response()->json($engineerMedicalData,200);
}

    public function getAllProviders()
    {
        //$service_providers = DboServiceProvider::All();
        $service_providers = DboServiceProvider::simplePaginate(15);
        return response()->json($service_providers,200);
    }

    
    public function getProviders(Request $request)
    {
        $providers = array();
        $where_array = array();
        if(isset($request->gov_id))   { $where_array['GovernorateId']   = $request->gov_id;  }; 
        if(isset($request->provider_id))   { $where_array['ServiceProviderID']   = $request->provider_id;  }
        if(isset($request->area_id))       { $where_array['PoliceStationId']     = $request->area_id;  }            
        if(isset($request->profession_id))       { $where_array['MedicalSpecialityId']     = $request->profession_id;  }            
        $providers = DboServiceProvider::select(['Email as email','ServiceProviderID as id','ServiceProviderName as name','Address as address','TelephoneNumber as phones','PoliceStationId as area_id','GovernorateId as governorate_id'])->where('ServiceProviderTypeId',$request->provider_type_id)->where('ServiceProviderStatusId','<>',3)->where($where_array)->get();
        
        return response()->json($providers,200);            
    }

    public function getProviderDetails(Request $request)
    {
        $providers = array();
        
        $where_array = array();
            if(isset($request->gov_id))   { $where_array['GovernorateId']   = $request->gov_id;  }; 
            if(isset($request->provider_id))   { $where_array['ServiceProviderID']   = $request->provider_id;  }
            if(isset($request->area_id))       { $where_array['PoliceStationId']     = $request->area_id;  }  
            if(isset($request->profession_id))       { $where_array['MedicalSpecialityId']     = $request->profession_id;  }          
            $providers = DboServiceProvider::select(['Email as email','ServiceProviderID as id','ServiceProviderName as name','Address as address','TelephoneNumber as phones','PoliceStationId as area_id','GovernorateId as governorate_id'])
                         ->where('ServiceProviderTypeId',$request->provider_type_id)
                         ->where('ServiceProviderStatusId','<>',3) // 3 Mean a3tezar
                         ->where($where_array)
                         ->first();
        return response()->json($providers,200);        
    }

    public function beneficiary_validate(Request $request)
    {
        $engineer = DboContact::where('dbo.Contact.OldRefID',$request->user_number)
                       ->leftjoin('dbo.MedBeneficiary','dbo.MedBeneficiary.ContactID','=','dbo.Contact.ContactID')
                       ->select('dbo.Contact.FullName', 'dbo.MedBeneficiary.Address' , 'dbo.MedBeneficiary.lastMedCareyear','dbo.MedBeneficiary.oldbenid')->first();
        
        if(!empty($engineer))
        {
            #
            $renewDate = new DateTime($engineer->LastRegDate);
            $today = Carbon::today();
            if(date("Y") <= $engineer->lastMedCareyear)
            {
                // can make request
                $engineer['status_code'] = 0;
                $_response               = $engineer;
               
            }
            else{
                // can`t makerequest
                $engineer['status_code'] = 1;
                $_response               = $engineer;
            }
        }
        else{
            # Empty
            $engineer = array('FullName' => NULL,'Address' => NULL,'lastMedCareyear' => NULL,'oldbenid' => NULL);
            $engineer['status_code'] = 2;
            $_response               = $engineer;
           
        }
        return response()->json($_response,200);
    }

}