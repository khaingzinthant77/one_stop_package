<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';
    protected $fillable = ['cust_id','img','path','ticket_id'];

    public function viewCustomer()
    {
    	return $this->hasOne('App\Inquiry','id','cust_id');
    }
}
