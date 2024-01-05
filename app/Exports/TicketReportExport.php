<?php

namespace App\Exports;

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

class TicketReportExport implements  FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function view(): View
    { 
        $team_id = (!empty($_POST['team_id']))?$_POST['team_id']:'';
        $from_date = (!empty($_POST['from_date']))?$_POST['from_date']:'';
        $to_date = (!empty($_POST['to_date']))?$_POST['to_date']:'';

        $amounts = new Amount();
        $amounts = $amounts->leftJoin('tickets','tickets.id','=','amounts.ticket_id')
                            ->leftjoin('surveys','surveys.id','=','tickets.cust_id')
                            ->leftjoin('customers', 'customers.id', '=', 'surveys.cust_id')
                            ->leftjoin('issue_types','issue_types.id','=','tickets.issue_id')
                            ->where('tickets.is_solve',1)->where('amounts.ticket_id','!=',null)
                            ->select('amounts.*','tickets.description','tickets.ticket_ID','customers.name','customers.phone_no','issue_types.issue_type');
       
        $ticket_ids = [];
        if ($team_id != null) {
            $ticket_assign = TicketAssign::where('team_id',$team_id)->where('is_solve',1)->select('ticket_id')->get();
            foreach ($ticket_assign as $key => $value) {
                array_push($ticket_ids, $value->ticket_id);
            }

            // dd($ticket_ids);
            $amounts = $amounts->whereIn('amounts.ticket_id',$ticket_ids);
        }

        $solved_ids = [];
        if ($from_date != null && $to_date != null) {
            $f_date = date('Y-m-d',strtotime($from_date)).' 00:59:59';
            $t_date = date('Y-m-d',strtotime($to_date)).' 23:59:59';

            $ticket_solved = TicketAssign::whereBetween('solved_date',[$f_date,$t_date])->where('is_solve',1)->select('ticket_id')->get();

            foreach ($ticket_solved as $key => $value) {
                array_push($solved_ids, $value->ticket_id);
            }

            // dd($ticket_ids);
            $amounts = $amounts->whereIn('amounts.ticket_id',$solved_ids);
        }
        
        $amounts = $amounts->get();
        return view('admin.ticket.report_export',compact('amounts'));
    }

}