<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstallItem extends Model
{
    protected $table ='install_items';
    protected $fillable =['survey_id','item_id','cat_id','brand_id','model_id','qty','price','amount','serial_no','cat_price','unit'];

    public function viewItem()
    {
    	return $this->hasOne('App\Item','id','item_id');
    }

    public function viewCategory()
    {
    	return $this->hasOne('App\Category','id','cat_id');
    }

    public function viewBrand()
    {
    	return $this->hasOne('App\Brand','id','brand_id');
    }
}
