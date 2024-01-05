<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarrantyPeriod extends Model
{
    protected $table = 'warranty_periods';
    protected $fillable = ['period'];
}
