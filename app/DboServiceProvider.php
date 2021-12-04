<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DboServiceProvider extends Model
{
    protected $table        = 'CostCards.ServiceProviderNew';
    protected $primaryKey   = 'ServiceProviderID';
}