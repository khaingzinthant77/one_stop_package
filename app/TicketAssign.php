<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketAssign extends Model
{
    protected $table = 'ticket_assigns';
    protected $fillable = ['ticket_id','team_id','assign_date','appoint_date','solved_date','is_solve','solved_by','admin_check','checked_by'];
}
