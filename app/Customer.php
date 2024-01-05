<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $fillable =['name','phone_no','tsh_id','address','c_type','lat','lng','cby','remark','created_at'];

    public function packages()
    {
        return $this->hasMany(CustomerHavePackage::class, 'customer_id');
    }
}
