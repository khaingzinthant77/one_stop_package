<?php

namespace App\Exports;

use App\Ticket;
use App\Amount;
use App\TicketAssign;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// for applying style sheet
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
Use \Maatwebsite\Excel\Sheet;

use DB;

class TicketExport implements  FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function view(): View
    { 
        $keyword = (!empty($_POST['keyword']))?$_POST['keyword']:'';
        $is_solve = (!empty($_POST['is_solve']))?$_POST['is_solve']:'';
        $team_id = (!empty($_POST['team_id']))?$_POST['team_id']:'';
        $issue_id = (!empty($_POST['issue_id']))?$_POST['issue_id']:'';
        $tsh_id = (!empty($_POST['tsh_id']))?$_POST['tsh_id']:'';
        $from_date = (!empty($_POST['from_date']))?date('Y-m-d',strtotime($_POST['from_date'])).' 00:00:59':null;
        $to_date = (!empty($_POST['to_date']))?date('Y-m-d',strtotime($_POST['to_date'])).' 23:59:59':null;
        
        $tickets = new TicketAssign();
        $tickets = $tickets->leftJoin('tickets',function($join){
                            $join->on('ticket_assigns.ticket_id', '=', 'tickets.id');
                        })
                        ->leftjoin('groups','groups.id','=','ticket_assigns.team_id')
                        ->leftJoin('surveys','surveys.id','=','tickets.cust_id')
                        ->leftjoin('customers','customers.id','=','surveys.cust_id')
                        ->leftjoin('townships','townships.id','=','customers.tsh_id')
                        ->leftjoin('issue_types','issue_types.id','=','tickets.issue_id')
                        ->select([
                            'customers.name',
                            'customers.phone_no',
                            'townships.town_name',
                            'customers.address',
                            'surveys.lat',
                            'surveys.lng',
                            'groups.group_name',
                            'ticket_assigns.assign_date',
                            'ticket_assigns.is_solve',
                            'ticket_assigns.team_id',
                            'ticket_assigns.ticket_id',
                            'issue_types.issue_type',
                            'tickets.description',
                            'tickets.id',
                            'ticket_assigns.solved_date',
                            'ticket_assigns.admin_check',
                            'ticket_assigns.checked_by'
                        ]);
        // dd($tickets->get());
        if ($keyword != null) {
            $tickets = $tickets->where('customers.name','like','%'.$keyword.'%');
        }
        
        if($is_solve != null){
            $tickets = $tickets->where('ticket_assigns.is_solve',$is_solve);
        }

        if ($team_id != null) {
            $tickets = $tickets->where('ticket_assigns.team_id',$team_id);
        }

        if ($issue_id != null) {
            $tickets = $tickets->where('ticket_assigns.issue_id',$issue_id);
        }

        if ($from_date != null && $to_date != null) {

            $tickets = $tickets->whereBetween('ticket_assigns.solved_date',[$from_date,$to_date]);
        }

        if ($tsh_id != null) {
            $tickets = $tickets->where('customers.tsh_id',$tsh_id);
        }
        $all_tickets = $tickets->get();
        $tickets = [];
        foreach ($all_tickets as $key => $value) {
            $value->service_charge = $this->get_service_charge($value->ticket_id);
            // dd($value);
            array_push($tickets, $value);
        }
        // dd($tickets);
        return view('admin.ticket.export',compact('tickets'));
    }

    public function get_service_charge($ticket_id)
    {
       $amounts = Amount::where('ticket_id',$ticket_id)->first();
       if ($amounts != null) {
           return $amounts->service_charge;
       }else{
            return 0;
       }
    }

}