<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestStatus extends Model
{
    protected $table        = 'dbo.RequestStatus';
/*
[RequestStatusID]
      ,[RequestStatusName]
      ,[CreatorID]
      ,[CreatDate]
      ,[ModifierID]
      ,[ModifyDate]
*/
    /*
     RequestStatusID	RequestStatusName
1	Êד ַב״בָ
2	דהÊול
3	דבÛל
4	Êד ַבׁÝײ
5	Êד ַבÊֳּםב
6	ַ׃ÊםÝֱַ ַזַׁÞ
7	דÞַָבֹ
8	Êד ַבדזַÝÞֹ
9	Êד ַבַÚÊדַֿ
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];
}
