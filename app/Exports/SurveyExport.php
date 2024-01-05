<?php

namespace App\Exports;

use App\Survey;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Assign;
use App\Township;
use App\Group;
// for applying style sheet
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use \Maatwebsite\Excel\Sheet;

use DB;

class SurveyExport implements FromView, ShouldAutoSize
{
  /**
   * @return \Illuminate\Support\Collection
   */

  public function view(): View
  {

    $tsh_id = isset($_POST['tsh_id']) ? $_POST['tsh_id'] : null;
    $team_id = isset($_POST['team_id']) ? $_POST['team_id'] : null;
    $assign_status = isset($_POST['assign_status']) ? $_POST['assign_status'] : null;
    $solve_status = isset($_POST['solve_status']) ? $_POST['solve_status'] : null;
    $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : null;
    $to_date = isset($_POST['to_date']) ? $_POST['to_date'] : null;
    $is_install = isset($_POST['is_install']) ? $_POST['is_install'] : null;

    $assigns = new Assign();
    $assigns = $assigns->leftJoin('surveys', function ($join) {
      $join->on('assigns.survey_id', '=', 'surveys.id');
    })
      ->leftjoin('groups', 'groups.id', '=', 'assigns.team_id')
      ->leftjoin('customers', 'customers.id', '=', 'surveys.cust_id')
      ->leftjoin('townships', 'townships.id', '=', 'customers.tsh_id')
      ->select([
        'surveys.*',
        'customers.name',
        'customers.phone_no',
        'townships.town_name',
        'customers.address',
        'groups.group_name AS group_name',
        'assigns.assign_date',
        'assigns.is_solve',
        'assigns.team_id',
        'assigns.admin_check',
        'assigns.checked_by'
      ])->where('survey_id', '!=', null)->where('surveys.is_solve', 0)->where('archive_status', 1);;


    if ($tsh_id != null) {
      $assigns = $assigns->where('tsh_id', $tsh_id);
    }

    if ($team_id != null) {
      $assigns = $assigns->where('team_id', $team_id);
    }

    if ($assign_status != null) {
      $assigns = $assigns->where('assign_status', $assign_status);
    }

    if ($solve_status != null) {
      $assigns = $assigns->where('surveys.is_solve', $solve_status);
    }

    if ($is_install != null) {
      $assigns = $assigns->where('surveys.is_install',$is_install);
    }

    if ($from_date != null && $to_date != null) {
      $from_date = date('Y-m-d', strtotime($from_date)) . " 00:00:00";
      $to_date = date('Y-m-d', strtotime($to_date)) . " 23:59:59";

      // dd($from_date,$to_date);
      $assigns = $assigns->whereBetween('surveys.created_at', [$from_date, $to_date]);
    }


    $assigns = $assigns->orderBy('surveys.created_at', 'DESC')->get();

    $townships = Township::all();
    $teams = Group::orderBy('group_name')->get();

    return view('admin.survey.export', compact('assigns', 'townships', 'teams'));
  }
}