<?php
use App\Group;
use App\TicketAssign;
use App\Assign;
use App\Ticket;
use App\Township;
use App\CustomerHavePackage;
use App\Customer;
//  get team member
if (!function_exists('get_team_member')) {
  function get_team_member($ticket_id)
  {
    // dd($ticket_id);
    $ticket_assign = new TicketAssign();
    $ticket_assign = $ticket_assign->leftJoin('groups','groups.id','=','ticket_assigns.team_id')->where('ticket_assigns.ticket_id',$ticket_id)->select('groups.group_name')->first()->group_name;
    // dd($ticket_assign);
    return $ticket_assign;
  }
}

if (!function_exists('get_teams')) {
  function get_teams()
  {
    $groups = Group::all();
    return $groups;
  }
}

if (!function_exists('get_townsips')) {
  function get_townsips()
  {
    $townships = Township::all();
    return $townships;
  }
}

function get_solved_date($survey_id)
{
    $assign = Assign::where('survey_id',$survey_id)->where('is_solve',1)->first();
    if ($assign != null) {
        return $assign->solved_date;
    }else{
        $ticket_assign = TicketAssign::where('ticket_id',$survey_id)->where('is_solve',1)->first();
        if ($ticket_assign != null) {
            return $ticket_assign->solved_date;
        }else{
            return null;
        }
        
    }
    
}

function getCustomerCount($tsh_id,$from_date,$to_date)
{
    $start_date = $from_date != null ? date('Y-m-d',strtotime($from_date)).' 00:00:00' : null;
    $end_date = $to_date != null ? date('Y-m-d',strtotime($to_date)).' 23:59:59' : null;

    if ($start_date != null && $end_date != null) {
        $count = Customer::where('tsh_id',$tsh_id)->where('c_type','package')->whereBetween('created_at',[$start_date,$end_date])->count();
    }
    else{
        $count = Customer::where('tsh_id',$tsh_id)->where('c_type','package')->count();
    }
    return $count;
}

function typeByCount($type,$from_date,$to_date)
{
    $start_date = $from_date != null ? date('Y-m-d',strtotime($from_date)).' 00:00:00' : null;
    $end_date = $to_date != null ? date('Y-m-d',strtotime($to_date)).' 23:59:59' : null;

    $customer_list = new Customer();

    if ($start_date != null && $end_date != null) {
        $customer_list = $customer_list->whereBetween('created_at',[$start_date,$end_date]);
    }

    $customer_list = $customer_list->whereHas('packages', function ($query) use ($type) {
                        $query->where('type', $type);
                    });

    return $customer_list->count();
}

function package_lists()
{
    return ['CCTV', 'Smart Home', 'mm-link Wifi', 'Fiber Internet', 'Computer & Mobile', 'Electronic'];
}

function getPackageCount($package,$type,$from_date,$to_date)
{
    $start_date = $from_date != null ? date('Y-m-d',strtotime($from_date)).' 00:00:00' : null;
    $end_date = $to_date != null ? date('Y-m-d',strtotime($to_date)).' 23:59:59' : null;

    $customer_list = new Customer();

    if ($start_date != null && $end_date != null) {
        $customer_list = $customer_list->whereBetween('created_at',[$start_date,$end_date]);
    }

    $customer_list = $customer_list->whereHas('packages', function ($query) use ($type,$package) {
                        $query->where('type', $type)
                            ->where('package',$package);
                    });

    return $customer_list->count();
}

// getPackageList
if (!function_exists('getPackageList')) {
    function getPackageList($type,$customer_id)
    {
        $package_list = CustomerHavePackage::where('customer_id',$customer_id)->where('type',$type)->get();
        
        return $package_list;
    }
}

if (!function_exists('get_assign_team')) {
  function get_assign_team($survey_id)
  {
    // $survey_id = 545;
    $assign = Assign::where('survey_id',$survey_id)->where('is_solve',1)->first();
   
    if ($assign != null) {
        if ($assign->team_id != null) {
            $group = Group::find($assign->team_id);

            return $group != null ? $group->group_name : null;
        }
        
    }else{
        return null;
        
    }
    // // dd($survey_id);
    // $assigns = new Assign();
    // $assigns = $assigns->leftJoin('surveys',function($join){
    //                         $join->on('assigns.survey_id', '=', 'surveys.id');
    //                     })
    //                   ->leftJoin('groups','groups.id','=','assigns.team_id')
    //                   ->where('surveys.cust_id',$survey_id)->select('groups.group_name')->first();
    // // return $assigns ? $assigns->group_name : "";

    // if ($assigns == null) {
    //   $tickets = new Ticket();
    //         $tickets = $tickets->leftJoin('issue_types','issue_types.id','=','tickets.issue_id')
    //                             ->select('tickets.*','issue_types.issue_type')
    //                             ->where('cust_id',$survey_id)->get();
    //         // dd($tickets[0]);
    //         if ($tickets->count()>0) {
    //             $ticket_assigns = new TicketAssign();
    //             $ticket_assigns = $ticket_assigns->leftJoin('groups','groups.id','=','ticket_assigns.team_id')
    //                         ->select('ticket_assigns.*','groups.group_name')
    //                         ->where('ticket_assigns.ticket_id',$tickets[0]->id)->first();
    //         }else{
    //             $ticket_assigns = null;
    //         }
            

    //         // dd($ticket_assigns);
    //       return $ticket_assigns ? $ticket_assigns->group_name : null;
    // }

    // return $assigns ? $assigns->group_name : null;
  }
}

function get_assign_ticket($ticket_id)
{
   
    $ticket_assign = TicketAssign::where('ticket_id',$ticket_id)->where('is_solve',1)->first();
    if ($ticket_assign != null) {
        $group = Group::find($ticket_assign->team_id);
        return $group != null ? $group->group_name : null;
    }else{
        return null;
    }
}

if (!function_exists('get_assign_team_id')) {
  function get_assign_team_id($survey_id)
  {
    // dd($survey_id);
    $assigns = new Assign();
    $assigns = $assigns->leftJoin('surveys',function($join){
                            $join->on('assigns.survey_id', '=', 'surveys.id');
                        })
                      ->leftJoin('groups','groups.id','=','assigns.team_id')
                      ->where('surveys.cust_id',$survey_id)->select('groups.id')->first();
    
    // dd($assigns);
    if ($assigns == null) {
      // dd("Here");
      // dd($survey_id);
      $tickets = new Ticket();
            $tickets = $tickets->leftJoin('issue_types','issue_types.id','=','tickets.issue_id')
                                ->select('tickets.*','issue_types.issue_type')
                                ->where('cust_id',$survey_id)->get();
            // dd($tickets[0]);
            if ($tickets->count()>0) {
                $ticket_assigns = new TicketAssign();
                $ticket_assigns = $ticket_assigns->leftJoin('groups','groups.id','=','ticket_assigns.team_id')
                            ->select('ticket_assigns.*','groups.id')
                            ->where('ticket_assigns.ticket_id',$tickets[0]->id)->first();
            }else{
                $ticket_assigns = null;
            }

            // dd($ticket_assigns);
            

            // dd($ticket_assigns);
          return $ticket_assigns ? $ticket_assigns->team_id : null;
    }

    // dd($assigns);

    return $assigns->id;
  }
}

if (!function_exists('get_solve_date')) {
  function get_solve_date($survey_id)
  {
    // dd($survey_id);
    $assigns = new Assign();
    $assigns = $assigns->leftJoin('surveys',function($join){
                            $join->on('assigns.survey_id', '=', 'surveys.id');
                        })
                      ->where('surveys.cust_id',$survey_id)->select('assigns.solved_date')->first();
    // return $assigns ? $assigns->group_name : "";

    if ($assigns == null) {
      $tickets = new Ticket();
            $tickets = $tickets->leftJoin('issue_types','issue_types.id','=','tickets.issue_id')
                                ->select('tickets.*','issue_types.issue_type')
                                ->where('cust_id',$survey_id)->get();
            // dd($tickets[0]);
            if ($tickets->count()>0) {
                $ticket_assigns = new TicketAssign();
                $ticket_assigns = $ticket_assigns
                            ->select('ticket_assigns.solved_date')
                            ->where('ticket_assigns.ticket_id',$tickets[0]->id)->first();
            }else{
                $ticket_assigns = null;
            }
            

            // dd($ticket_assigns);
          return $ticket_assigns ? $ticket_assigns->solved_date: null;
    }

    return $assigns ? $assigns->solved_date : null;
  }
}

if (!function_exists('get_assign_date')) {
  function get_assign_date($survey_id)
  {

    $assign = Assign::where('survey_id',$survey_id)->where('is_solve',1)->first();
   
    if ($assign != null) {

        return date('d-m-Y',strtotime($assign->assign_date));

    }else{
        return null;
        
    }

    // // dd($survey_id);
    // $assigns = new Assign();
    // $assigns = $assigns->leftJoin('surveys',function($join){
    //                         $join->on('assigns.survey_id', '=', 'surveys.id');
    //                     })
    //                   ->where('surveys.cust_id',$survey_id)->select('assigns.assign_date')->first();
    // // return $assigns ? $assigns->group_name : "";

    // if ($assigns == null) {
    //   $tickets = new Ticket();
    //         $tickets = $tickets->leftJoin('issue_types','issue_types.id','=','tickets.issue_id')
    //                             ->select('tickets.*','issue_types.issue_type')
    //                             ->where('cust_id',$survey_id)->get();
    //         // dd($tickets[0]);
    //         if ($tickets->count()>0) {
    //             $ticket_assigns = new TicketAssign();
    //             $ticket_assigns = $ticket_assigns
    //                         ->select('ticket_assigns.assign_date')
    //                         ->where('ticket_assigns.ticket_id',$tickets[0]->id)->first();
    //         }else{
    //             $ticket_assigns = null;
    //         }
            

    //         // dd($ticket_assigns);
    //       return $ticket_assigns ? $ticket_assigns->assign_date : null;
    // }

    // return $assigns ? $assigns->assign_date : null;
  }
}

if (!function_exists('get_assign_ticketdate')) {
  function get_assign_ticketdate($ticket_id)
  {

    $assign = TicketAssign::where('ticket_id',$ticket_id)->where('is_solve',1)->first();
   
    if ($assign != null) {
        
        return date('d-m-Y',strtotime($assign->assign_date));

    }else{
        return null;
        
    }

    }
}

