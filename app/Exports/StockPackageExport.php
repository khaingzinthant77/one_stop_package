<?php

namespace App\Exports;

use App\Survey;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Customer;

// for applying style sheet
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use \Maatwebsite\Excel\Sheet;

use DB;

class StockPackageExport implements FromView, ShouldAutoSize
{
  /**
   * @return \Illuminate\Support\Collection
   */

  public function view(): View
  {

    $tsh_id = isset($_POST['tsh_id']) ? $_POST['tsh_id'] : null;
    $team_id = isset($_POST['team_id']) ? $_POST['team_id'] : null;
    $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : null;
    $to_date = isset($_POST['to_date']) ? $_POST['to_date'] : null;
    $loc_id = isset($_POST['loc_id']) ? $_POST['loc_id'] : null;
    $package_id = isset($_POST['package_id']) ? $_POST['package_id'] : null;

    $customer_list = new Customer();
        $customer_list = $customer_list->leftJoin('townships','townships.id','=','customers.tsh_id')->select('customers.*','townships.town_name');
        
        if ($tsh_id != null) {
            $customer_list = $customer_list->where('tsh_id',$tsh_id);
        }

        if ($from_date != null && $to_date != null) {
            $from_date = date('Y-m-d',strtotime($from_date)).' 00:00:00';
            $to_date = date('Y-m-d',strtotime($to_date)).' 23:59:59';

            $customer_list = $customer_list->whereBetween('customers.created_at',[$from_date,$to_date]);
        }
      
        if ($team_id != null) {
            $customer_list = $customer_list->where('cby',$team_id);
        }

        if ($package_id != null) {
            $customer_list = $customer_list->whereHas('packages', function ($query) use ($package_id) {
                            $query->where('package', $package_id);
                        });
        }

        if ($loc_id != null) {
            $customer_list = $customer_list->whereHas('packages', function ($query) use ($loc_id) {
                            $query->where('type', $loc_id);
                        });
        }

        if ($package_id != null && $loc_id != null) {
            $customer_list = $customer_list->whereHas('packages', function ($query) use ($package_id,$loc_id) {
                            $query->where('type', $loc_id)
                                ->where('package', $package_id);
                        });
        }

        $customer_list = $customer_list->where('c_type','package')->orderBy('customers.created_at','desc')->get();

        return view('admin.one_stock_package.export', compact('customer_list'));
  }
}