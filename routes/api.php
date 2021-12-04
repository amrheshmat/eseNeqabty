<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


# Bonian Services 
Route::get('/ApiHealthCare/GetFollowersList','BonianController@GetFollowersList');
Route::post('/ApiHealthCare/MedBeneficiaryFollowersUpdate','BonianController@MedBeneficiaryFollowersUpdate');
Route::post('/apiPaymentRequest/AddHealthCareRequest','BonianController@AddHealthCareRequest');
Route::post('/apiPaymentRequest/CancelHealthCareRequest','BonianController@CancelHealthCareRequest');
Route::post('/apiPaymentRequest/SetPaid','BonianController@SetPaid');
Route::post('/apiPaymentRequest/AddRenewalRequest','BonianController@AddRenewalRequest');
Route::get('/apiPaymentRequest/InquiryProcess','BonianController@InquiryProcess');
Route::get('/apiPaymentRequest/HealthCareInquiryDetails','BonianController@HealthCareInquiryDetails');
Route::get('/apiPaymentRequest/RenewalInquiryDetails','BonianController@RenewalInquiryDetails');
Route::Post('/ApiHealthCare/Register','BonianController@Register');

Route::middleware(['localization'])->group(function () {
    Route::get('/services','ServiceController@index');
    Route::get('/service','ServiceController@serviceRequest');
    Route::post('/service','ServiceController@servicePayment');
   /* Route::get('/annualSubscription','ServiceController@membershipPaymentRequest');
    Route::post('/annualSubscription','ServiceController@membershipPayment');
    Route::get('/consultantSubscription','ServiceController@consultantInquiry');
    Route::get('/consultantSubscriptionPayment','ServiceController@consultantPayment');
    Route::get('/medicalSubscription','MedicalController@medicalService');*/
});

//Route::get('/service','ServiceController@getEngineer');


Route::group(['prefix'=>'v1'], function(){
    # Medical Services
    Route::group(['prefix'=>'Medical'], function(){
        Route::get('MedicalProviders'   ,'Dbo\MedicalController@getAllProviders');
        Route::post('providers'         ,'Dbo\MedicalController@getProviders');
        Route::post('providerDetails'   ,'Dbo\MedicalController@getProviderDetails');
        Route::post('Beneficiary'       ,'Dbo\MedicalController@beneficiary_validate');
        Route::get('engineer'          ,'Dbo\MedicalController@engineerMedicalData');
        Route::get('pullProviders'    ,'Dbo\MedicalController@pullProviders');
    });
    Route::POST('/Engineer','Dbo\EngineerController@getEngineer');
    Route::POST('/Update/Engineer','Dbo\EngineerController@updateEngineerInfo');
    Route::GET('/Update/Engineer','Dbo\EngineerController@updateEngineerInfo');
    # Engineering Records
    Route::post('RegistryDataValidation','Dbo\EngineeringRecordsController@RegistryDataValidation');
    
    
    
    # duplicate Payment Request ...
    Route::GET('/GetDublicatedPaymentRequest','Dbo\DuplicatePaymentRequestController@duplicateRequest');
    //Get Batch list...
    Route::GET('/getBatchList' ,'Dbo\BatchController@getBatchList');
    //Get Batch Card list...
    Route::GET('/getBatchCardList' ,'Dbo\BatchCardController@getBatchCardList');
    //Add New Batch ...
    Route::POST('/AddBatch' ,'Dbo\BatchController@addNewBatch');
    //create Corrupted Batch ...
    Route::POST('/CreateCorruptedBatch' ,'Dbo\BatchController@createCorruptedBatch');
    //Get Batch With Images ...
    Route::GET('/GetWithImages','Dbo\BatchController@getBatchWithImages');
     //Get Consolidate Batch .
    Route::GET('/GetConsolidateBatch','Dbo\BatchController@getConsolidateBatch');
     //Send Pending List ...
     Route::POST('/setPendingPrinting','Dbo\BatchController@setPendingPrinting');
    //Update Batch Status ...
    Route::POST('/UpdateBatchStatus','Dbo\BatchController@updateBatchStatus');
    //Get Bending list...
    Route::GET('/getBendingList' ,'Dbo\BatchController@getBendingList');
    //send paymentRequest sms
    Route::POST('/setPendingPrintingSmsStatus','Dbo\SmsStatusController@setPendingPrintingSmsStatus');
});

// Data Celan
//Route::get('Engineer/{OldRefID:[0-9]+}',array('as'=>'Engineer.get','uses'=>'Dbo\EngineerController@getEngineer'));
//must be Post
/*
Route::get('Engineer/phone_code/{OldRefID:[0-9]+}',array('as'=>'Engineer.getPhoneCode','uses'=>'Dbo\EngineerController@getPhoneCode'));
Route::get('Engineer/check_code/{OldRefID:[0-9]+}',array('as'=>'Engineer.checkCode','uses'=>'Dbo\EngineerController@checkCode'));
Route::get('Engineer/PhoneVerification/{OldRefID:[0-9]+}/{phone_number:[0-9]+}/{phone_code:[0-9]+}',array('as'=>'Engineer.PhoneVerification','uses'=>'Dbo\EngineerController@PhoneVerification'));
// END Data Celan
*/
//Update NFC ...
Route::POST('/ApiBonianBatchCard/UpdateCardListNFC','Dbo\BatchController@updateBatch');

/*Route::group(['prefix'=>'Spp'], function(){
    Route::GET('/Spp','Dbo\SppController@getList');
    Route::GET('/GetViewModel','Dbo\SppController@getViewModel');
    Route::GET('/GetIndexViewModel','Dbo\SppController@getIndexViewModel');
    Route::Delete('/Delete','Dbo\SppController@delete');
    Route::PUT('/Update','Dbo\SppController@update');
    Route::PUT('/Execute','Dbo\SppController@execute');
    Route::POST('/Add','Dbo\SppController@add');
});*/
Route::GET('/ApiBonianBatch/GetLast' ,'Dbo\ApiBatchController@GetLast');
Route::GET('/ApiBonianBatch/GetNewBatchList' ,'Dbo\ApiBatchController@GetNewBatchList');
Route::DELETE('/ApiBatch/Delete' ,'Dbo\ApiBatchController@Delete');
Route::GET('/ApiBonianBatch/Get' ,'Dbo\ApiBatchController@Get');
Route::POST('/ApiBonianBatch/UpdateBatchStatus','Dbo\ApiBatchController@UpdateBatchStatus');
Route::POST('/ApiBonianBatchCard/UpdateBatchCardStatus','Dbo\ApiBatchController@UpdateBatchCardStatus');
Route::POST('/ApiBonianBatchCard/UpdateCardListStatus','Dbo\ApiBatchController@UpdateCardListStatus');

Route::GET('{path}', 'Dbo\SppController@getRedirect')->where('path', '.*');
Route::POST('{path}', 'Dbo\SppController@postRedirect')->where('path', '.*');
Route::PUT('{path}', 'Dbo\SppController@putRedirect')->where('path', '.*');
Route::DELETE('{path}', 'Dbo\SppController@deleteRedirect')->where('path', '.*');





