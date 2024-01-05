<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $table = 'team_members';
    protected $fillable = ['leader_id','member_id'];

    public function members()
    {
        return $this->hasOne('App\Technician','id','member_id');
    }
}
