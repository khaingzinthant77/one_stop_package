<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceCharge extends Model
{
    protected $table = 'servicecharge';
    protected $fillable =['name','price'];
}
