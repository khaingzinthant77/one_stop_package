<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamLeader extends Model
{
    protected $table ='team_leaders';

    protected $fillable = ['team_id','team_status','tech_id'];
}
