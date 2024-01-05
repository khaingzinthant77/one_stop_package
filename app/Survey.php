<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table = 'surveys';
    protected $fillable = ['cust_id','survey_by','survey_name','lat','lng','is_solve','c_code','assign_status','remark','survey_type','admin_check','checked_by','archive_status','is_install','not_install_remark'];
}
