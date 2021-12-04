<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table        = 'dbo.Invoice';
    protected $primaryKey = 'InvoiceID';
    public $timestamps = false;

/*
[InvoiceID] => primary
,[Total]
,[ReceiptNo]
,[PaymentType]
,[Date]
,[CreatorID]
,[CreatDate]
,[ModifierID]
,[ModifyDate]
,[ContactID]
,[IsCanceled]
,[CanceledComment]
,[CanceledDate]
,[HealthCareReceiptNo]
,[HealthCareEmpReceiptNo]
*/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Total','Date','CreatDate','CreatorID','ContactID','InvoiceID','HealthCareReceiptNo'
    ];
}
