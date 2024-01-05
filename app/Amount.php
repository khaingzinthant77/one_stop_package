<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Amount extends Model
{
    protected $table = 'amounts';
    protected $fillable = ['survey_id','ticket_id','sub_total','total_amt','install_charge','service_charge','is_cloud','cloud_charge','is_foc','discount','cabling_charge','on_call_charge'];
}
