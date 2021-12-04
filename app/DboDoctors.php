<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DboDoctors extends Model
{
    protected $table        = 'CostCards.Doctor';
    protected $primaryKey   = 'Doctorid';
    public $timestamps      = false;
}
