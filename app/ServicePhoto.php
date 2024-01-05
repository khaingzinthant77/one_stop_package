<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicePhoto extends Model
{
    protected $table = 'service_photos';
    protected $fillable = ['survey_id','ticket_id','path','img','status'];

    public function survey()
    {
        $this->hasOne('App\Survey','id','survey_id');
    }
}
