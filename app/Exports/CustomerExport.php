<?php

namespace App\Exports;

use App\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;
use App\Assign;
class CustomerExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    { 
        $tsh_id = (!empty($_POST['tsh_id']))?$_POST['tsh_id']:'';
        $team_id = (!empty($_POST['team_id']))?$_POST['team_id']:'';
        $assign_status = (!empty($_POST['assign_status']))?$_POST['assign_status']:'';
        $solve_status = (!empty($_POST['solve_status']))?$_POST['solve_status']:'';
        $from_date = (!empty($_POST['from_date']))?$_POST['from_date']:'';
        $to_date = (!empty($_POST['to_date']))?$_POST['to_date']:'';

        $assigns = new Assign();
        $assigns = $assigns->leftJoin('surveys',function($join){
                            $join->on('assigns.survey_id', '=', 'surveys.id');
                        })
                        ->leftjoin('groups','groups.id','=','assigns.team_id')
                        ->leftjoin('customers','customers.id','=','surveys.cust_id')
                        ->leftjoin('townships','townships.id','=','customers.tsh_id')
                        ->select([
                            'surveys.c_code',
                            'customers.name',
                            'customers.phone_no',
                            'townships.town_name',
                            'customers.address',
                            'groups.group_name AS group_name',
                            'assigns.assign_date',
                            'assigns.solved_date'
                        ])->where('survey_id','!=',null)->where('surveys.is_solve',1);
        
        if ($tsh_id != null) {
            $assigns = $assigns->where('tsh_id',$tsh_id);
        }
 
        if ($team_id != null) {
            $assigns = $assigns->where('team_id',$team_id);
        }

        if ($assign_status != null) {
            $assigns = $assigns->where('assign_status',$assign_status);
        }

        if ($solve_status != null) {
            $assigns = $assigns->where('is_solve',$solve_status);
        }

        if ($from_date != null && $to_date != null) {
            $from_date = date('Y-m-d',strtotime($from_date))." 00:00:00";
            $to_date = date('Y-m-d',strtotime($to_date))." 23:59:59";

            // dd($from_date,$to_date);
            $assigns = $assigns->whereBetween('surveys.created_at',[$from_date,$to_date]);
        }

        return $assigns->get();
    }

    public function headings(): array
    {
        return [
            "Customer Code",
            "Name",
            "Phone No",
            'Township',
            'Address',
            'Team',
            'Assign Date',
            'Solved Date'
        ];
    }
}