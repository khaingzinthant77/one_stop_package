<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos';
    protected $fillable = ['survey_id','ticket_id','path','photo_name','is_survey'];

    public function survey()
    {
        return $this->hasOne('App\Survey','id','survey_id');
    }

    // public function ticket()
    // {
    //     return $this->hasOne('App\Survey','id','survey_id');
    // }
}
