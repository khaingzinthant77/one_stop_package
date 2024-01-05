<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    protected $table = 'signatures';
    protected $fillable = ['survey_id','ticket_id','path','cust_sign','tech_sign','cust_sign_image','tech_sign_image'];

    public function survey()
    {
        $this->hasOne('App\Survey','id','survey_id');
    }

}
