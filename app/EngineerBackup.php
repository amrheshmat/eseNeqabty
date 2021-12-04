<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EngineerBackup extends Model
{
    protected $table        = 'dbo.EngineerBackup';
    protected $primaryKey = 'ID';
    public $timestamps = false;

/*
[ID]
      ,[EngID]
      ,[LastRegDate]
      ,[LastRegYear]
      ,[LastConsYear]
      ,[LastConsDate]
      ,[ReceiptNo]
      ,[Status]
      ,[CreationDate]
      ,[CreatorID]
      ,[IsDoctor]
      ,[EducationLevelID]
      ,[ConsultantRegDate]
      ,[ContactType]
      ,[ContactGroup]
      ,[CurrentRegYear]
      ,[CurrentConsYear]
*/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'EngID','LastRegDate','LastRegYear','LastConsYear','LastConsDate','ReceiptNo','Status',
     'CreationDate','CreatorID','IsDoctor','EducationLevelID', 'ConsultantRegDate', 'ContactGroup' ,
      'ContactGroup','CurrentRegYear','CurrentConsYear'
    ];
}
