<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $fillable =['cat_id','brand_id','model','is_serialno','qty','unit','price','remark','photo','path','status','item_code'];
     public function viewCategory()
    {
    	return $this->hasOne('App\Category','id','cat_id');
    }
    public function viewBrand()
    {
    	return $this->hasOne('App\Brand','id','brand_id');
    }

    public function serials(){
        return $this->hasMany('App\ProductSerial');
    }
}
