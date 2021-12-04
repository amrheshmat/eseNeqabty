<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $table        = 'dbo.ServiceRequest';
    protected $primaryKey = 'RequestID';
    public $timestamps = false;
/*
[RequestID] => primary
,[ContactID] => Contacts
,[RequestDate] => date time
,[RequestStatus] => 11 ready / 10 paid
*/

  //  public with=['serviceRequestDetails'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ContactID',
    ];
    //set the default values for attributes
    protected $attributes=['RequestStatus'=>11];

    /**
     * Get the serviceRequestsDetails for the current Request
     */
    public function serviceRequestDetails()
    {
        return $this->hasOne(ServiceRequestDetails::class,'RequestID','RequestID');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

}
