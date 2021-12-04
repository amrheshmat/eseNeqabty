<?php

namespace App\Http\Controllers\Dbo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Engineer;
use App\Contact;
class EngineerController extends Controller
{
  
    //get Data Clean info
   /* public function getEngineer(Request $request, $OldRefID){
        $getEngineer   = $this->Engineer->getEngineer($OldRefID); 
        if(!empty($getEngineer->OldRefID)){
            $checkEngineer =  \App\Engineer::firstOrCreate(['OldRefID' =>$OldRefID],['phone_number'=>@$getEngineer->phone_number,'email'=>@$getEngineer->email,'gender'=>@$getEngineer->gender,'birthdate'=>@$getEngineer->birthdate,'national_id'=>@$getEngineer->national_id,'full_name'=>@$getEngineer->full_name]);
            $checkEngineer  = \App\Engineer::find($checkEngineer->id);
            return response()->json($checkEngineer,200)->header('Content-Type', 'application/json;charset=UTF-8');        
        }
            return response()->json('thereisnodataforthisid',200)->header('Content-Type', 'application/json;charset=UTF-8');
    }*/
    public function getEngineer(Request $request){ 
        $isEngineer = Engineer::where('OldRefID',$request->OldRefID)->first();
       if(!empty($isEngineer)){
       if($isEngineer->contact->BirthDate != null){
        $date =substr($isEngineer->contact->BirthDate,0,10);
       }else{
        $date =$isEngineer->contact->BirthDate;
       }
           
           //$birthdate= $date->format('Y-m-d');
               $_response['OldRefID'] = $isEngineer->contact->OldRefID;
               $_response['name'] = $isEngineer->contact->FullName;
               $_response['address'] = $isEngineer->contact->Address;
               $_response['phone'] = $isEngineer->contact->Telephone;
               $_response['mobile'] = $isEngineer->contact->Mobile;
               $_response['email'] = $isEngineer->contact->Email;
               $_response['birthdate'] = $date;
               $_response['graduationyear'] = $isEngineer->GraduationYear;
               $_response['PassportNumber'] = $isEngineer->PassportNumber;
               $_response['NationalNumber'] = $isEngineer->contact->NationalNumber;
               /*
               if($isEngineer->contact->MaritalStatusID == 1)
                   $_response['martialStatus'] = 'اعزب';
               else if($isEngineer->contact->MaritalStatusID == 2)
                   $_response['martialStatus'] = 'متزوج';
               else if($isEngineer->contact->MaritalStatusID == 3)
                   $_response['martialStatus'] = 'مطلق';
               else if($isEngineer->contact->MaritalStatusID == 4)
                   $_response['martialStatus'] = 'ارمل';
               else if($isEngineer->contact->MaritalStatusID == 5)
                   $_response['martialStatus'] = 'مطلق ويعول';
               else if($isEngineer->contact->MaritalStatusID == 9)
                   $_response['martialStatus'] = 'غير مبين';
                   */
              return response()->json($_response,200);   
       }else{
           return response()->json('not engineer',200);  
       }
           
   }
   public function updateEngineerInfo(Request $request){ 
    $isEngineer = Engineer::where('OldRefID',$request->OldRefID)->first();
    $isContact = Contact::where('OldRefID',$request->OldRefID)->first();
    if(!empty($isEngineer) && !empty($isContact) ){
        $isContact->FullName =$request->name;
        $isContact->Address =$request->address;
        $isContact->Telephone =$request->phone;
        $isContact->Mobile =$request->mobile;
        $isContact->Email =$request->email;
        $isContact->BirthDate =$request->birthdate;
        $isEngineer->GraduationYear =$request->graduationyear;
         $isEngineer->PassportNumber =$request->PassportNumber;
        // $isContact->MaritalStatusID =$request->martialStatus;
     // $isEngineer->PassportNationalityID =$request->PassportNationalityID;
       // $isEngineer->NationalityID =$request->NationalityID;
       $saved = array();
       $saved['PassportNumber'] =$request->PassportNumber;
       $saved['graduationyear'] =$request->graduationYear;
       $saved['birthDate'] =$request->birthDate;
       $saved['email'] =$request->email;
       $saved['mobile'] =$request->mobile;
        $isContact->NationalNumber =$request->NationalNumber;
        $isEngineer->save();
        $isContact->save();
         return response()->json($saved,200);   
        }else{
        return response()->json('error saving data',200);   
        }
     
       
    
}

    //Send SMS Code
    public function getPhoneCode(Request $request, $OldRefID){
        $find               = \App\Engineer::where('OldRefID', $OldRefID)->first();
        $updatedEngineer    = $find->update(['phone_code' => mt_rand(100000, 999999),'phone_number'=>$request->phone_number]);        
        return response()->json($find,200)->header('Content-Type', 'application/json;charset=UTF-8');
    }

    public function checkCode(Request $request, $OldRefID){ 
        $find   = \App\Engineer::where('OldRefID', $OldRefID)->where('phone_code',$request->phone_code)->first();
        if($find){
            $update = $find->update(['phone_verified' =>'yes']);
            if($update){
                \App\Contact::where('OldRefID',$OldRefID)->update(['Mobile' =>$find->phone_number]);
                return response()->json($update,200);
            }
        }
        
        return response()->json(['message'=>'error'],422);
        
             
    }
        
    //check Phone Virefication
    public function PhoneVerification($OldRefID,$phone_number,$phone_code)
    {
        $find               = \App\Engineer::where(['OldRefID', $OldRefID],['phone_number',$phone_number],['phone_code',$phone_code])->update(['phone_verified' =>'yes']);
        return dd($find);

    }
}
