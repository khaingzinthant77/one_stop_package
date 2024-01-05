<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Survey;
use App\Ticket;
use App\ServiceCharge;
use App\Assign;
use App\TicketAssign;
use App\Group;

class DashboardApiController extends Controller
{
	public function get_dashboard_data(Request $request)
	{
		$survey_count = Survey::where('survey_by',$request->team_id)->get()->count();
		$new_cust_count = Assign::where('team_id',$request->team_id)->where('survey_id','!=',null)->whereDate('assign_date',date('Y-m-d'))->where('is_solve',0)->get()->count();

		$left_cust_count = Assign::where('team_id',$request->team_id)->where('survey_id','!=',null)->whereDate('assign_date','!=',date('Y-m-d'))->where('is_solve',0)->get()->count();

		
		$assigns = new Assign();
        $install_cust_count = $assigns->leftJoin('surveys',function($join){
                            $join->on('assigns.survey_id', '=', 'surveys.id');
                        })
                        ->leftjoin('groups','groups.id','=','assigns.team_id')
                        ->leftjoin('customers','customers.id','=','surveys.cust_id')
                        ->leftjoin('townships','townships.id','=','customers.tsh_id')
                        ->select([
                            'surveys.id',
                            'surveys.lat',
                            'surveys.lng',
                            'customers.name',
                            'customers.phone_no',
                            'townships.town_name',
                            'customers.address',
                            'surveys.is_solve',
                            'assigns.solved_date'
                        ])->where('survey_id','!=',null)->where('assigns.team_id',$request->team_id)->where('assigns.is_solve',1)->where('survey_type',1)->get()->count();

	        $solve_assign = new TicketAssign();            
	        $solve_count = $solve_assign->leftJoin('tickets',function($join){
	                            $join->on('ticket_assigns.ticket_id', '=', 'tickets.id');
	                        })
	        				
	       				 	->where('team_id',$request->team_id)->where('ticket_assigns.is_solve',1)
	                       ->get()->count();
	        // dd($solve_count);
	        // ->where('ticket_id','!=',null)->where('assigns.is_solve',1)->where('surveys.survey_type',2)

		// $solve_count = Assign::where('team_id',$request->team_id)->where('ticket_id','!=',null)->where('is_solve',1)->where('survey_type',2)->get()->count();

		// $unsolve_count = Assign::where('team_id',$request->team_id)->where('ticket_id','!=',null)->whereDate('assign_date',date('Y-m-d'))->where('is_solve',0)->get()->count();

        $unsolve_assign = new TicketAssign();            
        $unsolve_count = $unsolve_assign->leftJoin('tickets',function($join){
                            $join->on('ticket_assigns.ticket_id', '=', 'tickets.id');
                        })
                        ->where('team_id',$request->team_id)->where('ticket_assigns.is_solve',0)
                       ->get()->count();

    	return response([
                    'survey_count'=>$survey_count,
                    'new_cust_count'=>$new_cust_count,
                    'left_cust_count'=>$left_cust_count,
                    'install_count'=>$install_cust_count,
                    'unsolve_count'=>$unsolve_count,
                    'solve_count'=>$solve_count,
                    'message'=>"Success",
                    'status'=>1
            ]);
	}

    public function admin_dashboard()
    {
       $survey_count = Survey::whereDate('created_at',date('Y-m-d'))->get()->count();
        $install_count = new Assign();
        $install_count = $install_count->leftJoin('surveys',function($join){
                            $join->on('assigns.survey_id', '=', 'surveys.id');
                        })
                        ->leftjoin('groups','groups.id','=','assigns.team_id')
                        ->leftjoin('customers','customers.id','=','surveys.cust_id')
                        ->leftjoin('townships','townships.id','=','customers.tsh_id')
                        ->whereDate('solved_date',date('Y-m-d'))->where('surveys.is_solve',1)->get()->count();

        $solve_ticket_count = new TicketAssign();
        $solve_ticket_count = $solve_ticket_count->leftJoin('tickets',function($join){
                            $join->on('ticket_assigns.ticket_id', '=', 'tickets.id');
                        })
                        ->leftjoin('groups','groups.id','=','ticket_assigns.team_id')
                        ->leftJoin('surveys','surveys.id','=','tickets.cust_id')
                        ->leftjoin('customers','customers.id','=','surveys.cust_id')
                        ->leftjoin('townships','townships.id','=','customers.tsh_id')
                        ->leftjoin('issue_types','issue_types.id','=','tickets.issue_id')
                       ->whereDate('solved_date',date('Y-m-d'))->where('tickets.is_solve',1)->get()->count();
        $teams = Group::orderBy('group_name')->get();
        foreach ($teams as $key => $team) {
            $team->cust_left = $this->get_cust_count($team->id,1);
            $team->cust_install = $this->get_cust_count($team->id,2);
            //ticket
            $team->solved = $this->get_ticket_count($team->id,1);
            $team->unsolve = $this->get_ticket_count($team->id,2);
           
        }

        return response([
                    'survey_count'=>$survey_count,
                    'install_count'=>$install_count,
                    'solve_ticket_count'=>$solve_ticket_count,
                    'teams'=>$teams,
                    'message'=>"Success",
                    'status'=>1
            ]);
    }

    public function get_cust_count($team_id,$status)
    {
        
        if ($status == 1) {
            $count = Assign::where('team_id',$team_id)->where('survey_id','!=',null)->where('is_solve',0)->get()->count();
        }

        if ($status == 2) {
            $count = Assign::where('team_id',$team_id)->where('survey_id','!=',null)->where('is_solve',1)->whereDate('solved_date',date('Y-m-d'))->get()->count();
        }

        return $count;
        
    }

    public function get_ticket_count($team_id,$status)
    {
        if ($status == 1) {
            $count = TicketAssign::where('team_id',$team_id)->where('ticket_id','!=',null)->where('is_solve',1)->get()->count();
        }
        if ($status == 2) {
            $count = TicketAssign::where('team_id',$team_id)->where('ticket_id','!=',null)->where('is_solve',0)->get()->count();
        }

        return $count;
    }
}