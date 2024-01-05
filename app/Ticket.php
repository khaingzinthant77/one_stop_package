<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';
    protected $fillable =['cust_id','issue_id','description','is_solve','remark'];

    public function viewSurvey()
    {
    	return $this->hasOne('App\Survey','id','cust_id');
    }
    public function viewIssue()
    {
        return $this->hasOne('App\IssueType','id','issue_id');
    }
    
}
