<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceRequestDetails extends Model
{
    protected $table        = 'dbo.ServiceRequestDetails';
    protected $primaryKey = 'ServiceReqDetID';
    public $timestamps = false;

/*
 [ServiceReqDetID] => primary
,[RequestID] => ServiceRequest
,[ServiceID]=> ServiceEngineer 2 subs / 6 consult
,[Quantity]=> default 1
,[RequestStatus] => foreign RequestStatus 11 ready/ 10 paid
,[AttachmentID] => related to attachment table/ null
,[InvoiceID]=> Invoices in case of payment / default null
,[TotalPrice] => the total calculated amount of charges
,[Active]
,[CreatorID] ?? neqabty  id
,[CreatDate] current date
,[ModifierID]
,[ModifyDate]
,[FilePath]
,[CanceledReason]
,[CanceledDate]
,[CanceledUser]
,[CostCardNotificationDocID]
*/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'RequestID','ServiceID','RequestStatus','InvoiceID','TotalPrice','CreatorID','CreatDate',
        'ModifierID','ModifyDate','CanceledReason','CanceledDate','CanceledUser'
    ];
    //set the default values for attributes
    protected $attributes=['Quantity'=>1];

    /**
     * return details to the related ServiceRequest
     */
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class,'RequestID','RequestID');
    }

    /**
     * One to many relation with RequestChargesTransaction
     */
    public function requestChargesTransactions()
    {
        return $this->hasMany(RequestChargesTransaction::class);
    }



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];


}
