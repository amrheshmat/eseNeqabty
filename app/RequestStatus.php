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
1	�� �����
2	�����
3	����
4	�� �����
5	�� �������
6	������� �����
7	������
8	�� ��������
9	�� ��������
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];
}
