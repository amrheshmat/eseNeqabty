<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Engineer;
use App\ChargeItem;
use Carbon\Carbon;
use App\ServiceEngineer;
use App\MedBeneficiary;
use App\HealthCareFees;
use App\MedRegRoles;
use App\MedRegRolesDetails;

class SampleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $oldRefID
     * @return \Illuminate\Http\Response
     */

    // تجديد الرعايه الصحيه Open
    protected function medicalService(Request $request, $oldRefID){
  //============================================================================
        $serviceEngineer = ServiceEngineer::where('ServiceID',5194)->first();
        $engineer = Engineer::where('OldRefID',$oldRefID)->first();
        $medBeneficiary = MedBeneficiary::where('ContactID',$engineer->contact->ContactID)->get();
  //============================================================================
        //check the period of registiration in medical service ...
            $CurrentDate    = date('Y-m-d 00:00:00.000');
            $CurrentYear    = date('Y');
            $GraduationYear = $engineer->GraduationYear;
            $MedRegDate     = \App\MedRegDate::where('StartDate','<=', $CurrentDate)
            ->Where('EndDate','>=', $CurrentDate)->where('IsMandatory',1)->first(); //تاريخ استحقاق الخدمه الطبيه
            if(empty($MedRegDate)){
                echo "التسجيل غير مسموح به في هذه الفتره";
                 return null;
            }
 //==============================================================================
        //check if engineer register  in medical service or no ...
        if(empty($medBeneficiary )){
            echo 'غير مستحق خدمت الطبيه(غير مسجل)';
            return   null;
        }
        //check if will pay resubscribe fees or no
       if($medBeneficiary->lastMedCareyear < $CurrentYear - 1){
           $engineerHealthCareFees  = HealthCareFees::where('FeesRegYear',$MedRegDate->Year)->Where('IsEngineer',1)->get();
       }else{
           $engineerHealthCareFees  = HealthCareFees::where('FeesRegYear',$MedRegDate->Year)->where('FeesID','!=',20)->Where('IsEngineer',1)->get();
       }
 //==============================================================================
        // the value of engineer subscrib only ...
            $MedRegRoles        = MedRegRoles::where('RegYear',$MedRegDate->Year)//قيمه الخدمه بناءً علي فتره التخرج
            ->where('GFromYear','<=', $GraduationYear)->Where('GToYear','>=', $GraduationYear)
            ->orWhereNull('GFromYear')->orWhereNull('GToYear')->where('IsEngineer',1)
            ->where('RegStatusID',$engineer->RegStatusID)->first();//the value in picture

        $roleDetails = MedRegRolesDetails::where('RoleID',$MedRegRoles->RoleID);

        
            //============================
            
            /*
         * 1- engineer
         * 2-wife
         * 3-son
         * 4-parent
         * */
         //   $DeceaseDate        = ($engineer->contact->DeceaseDate) ; //تاريخ المرض
//==================================================================================
        /*
        // GET HealthCareFees  مصاريف الخدمات الاداريه للمهندس والتابعين له
            $engineerHealthCareFees     = \App\HealthCareFees::where('FeesRegYear',$MedRegDate->Year)->where('Active',1)->Where('IsEngineer',1)->get();
            $benficiaryHealthCareFees    = \App\HealthCareFees::where('FeesRegYear',$MedRegDate->Year)->where('Active',1)->Where('IsEngineer',0)->get();
//===================================================================================
        // Engineer medical care  information
           /* echo 'ContactID : ' .           $MedBeneficiary->ContactID.'<br/>';
            echo 'Graduation Year : '.      $engineer->GraduationYear.'<br/>';
            echo 'Engineer Name : '.        $MedBeneficiary->BENNAME.'<br/>';
            echo 'last MedCare Year : '.    $MedBeneficiary->lastMedCareyear.'<br/>';
            echo ($DeceaseDate) ? '' : 'Graduation Year between:'.$MedRegRoles->GFromYear.' - '.$MedRegRoles->GToYear.'<br/>';
            echo ($DeceaseDate) ? 'DeceaseDate : '.$DeceaseDate.'<br/>' : '';
            if(empty($DeceaseDate)){
                foreach($engineerHealthCareFees as $HealthCareFees){
                    $RegistrationYearDifference =  $HealthCareFees->FeesRegYear - $MedBeneficiary->lastMedCareyear;
                    if($RegistrationYearDifference > $HealthCareFees->RegistrationYearDifference){
                        echo $HealthCareFees->FeesName.' : '.$HealthCareFees->FeesMinValue.'<br/>';
                    }
                }
            }

            echo 'Value : '. $MedRegRoles->Value.'<br/>';
            echo '<hr/>Beneficiaries<hr/><br/>';
//=========================================================================================
        // DboMedBeneficiary التابعين
            foreach($benficiaryHealthCareFees->RELATEDTO as $RELATEDTO){
                /*
                echo 'Beneficiary Name  : ' .$RELATEDTO->BENNAME.'<br/>';
                echo 'Beneficiary Type : '  .$RELATEDTO->RELATIONTYPEBYOWNER->RelationTypeName.'<br/>';
                echo 'Value : '             .$MedRegRolesDetails->firstWhere('RelationTypeID',$RELATEDTO->RELATIONTYPE)->Value.'<br/>';
                echo 'last MedCare Year : '. $RELATEDTO->lastMedCareyear.'<br/>';
                foreach($benficiaryHealthCareFees as $HealthCareFees){
                    $RegistrationYearDifference =  $HealthCareFees->FeesRegYear - $RELATEDTO->lastMedCareyear;
                    if($RegistrationYearDifference > $HealthCareFees->RegistrationYearDifference){
                            echo $HealthCareFees->FeesName.' : '.$HealthCareFees->FeesMinValue.'<br/>';
                    }
                }
                echo '<hr/>';
            }*/
//================================================================================================
    }
}
//used table in this function ...
/*
1-ServiceEngineer  ... الخدمات المتاحه
2-Engineer ... المهندسين (رقم العضويه القديم
3-MedRegDate ...  تاريخ استحقاق الخدمه العلاجيه
4-MedRegRoles ... القيم المستحقه لكل فتره بناءً علي تاريخ التخرج
5-medRegRolesDetails ...
6-MedBeneficiary ...
7-HealthCareFees ...
 */
