<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyInstallItem extends Model
{
    protected $table = 'survey_install_items';
    protected $fillable = ['survey_id','ticket_id','item_id','item_price','cat_id','cat_price','qty','amount','is_serial_no','serial_no'];

    public function survey()
    {
        $this->hasOne('App\Survey','id','survey_id');
    }

    public function item()
    {
        $this->hasOne('App\Item','id','item_id');
    }

    public function category()
    {
        $this->hasOne('App\Category','id','cat_id');
    }

    public function brand()
    {
        $this->hasOne('App\Brand','id','brand_id');
    }
}
