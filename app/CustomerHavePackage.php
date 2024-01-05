<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerHavePackage extends Model
{
    protected $table = 'customer_have_packages';
    protected $fillable = ['customer_id','type','package'];
}
