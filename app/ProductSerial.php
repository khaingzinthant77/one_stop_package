<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSerial extends Model
{
   protected $table='productserial';
   protected $fillable=['item_id','serial_no','status'];
    public function viewItem()
    {
    	return $this->hasOne('App\Item','id','item_id');
    }
}
