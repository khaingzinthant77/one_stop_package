<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assign extends Model
{
    protected $table = 'assigns';
    protected $fillable = ['survey_id','team_id','assign_date','appoint_date','solved_date','is_solve','solved_by','admin_check','checked_by'];

    public function survey()
    {
        return $this->hasOne('App\Survey','id','survey_id');
    }

    // public function ticket($value='')
    // {
    //     // code...
    // }

    public function team()
    {
        return $this->hasOne('App\Group','id','team_id');
    }

    
}
