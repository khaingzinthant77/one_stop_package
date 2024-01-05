<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Township;
use App\Survey;
use App\Group;
use App\Assign;
use App\SurveyInstallItem;
use App\CustomerHavePackage;
use App\Amount;
use App\Ticket;
use App\TicketAssign;
use App\Category;
use App\Setting;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $townships = Township::all();
        foreach ($townships as $key => $township) {
            $township->new_count = $this->get_count($township->id,1);
            $township->service_count = $this->get_count($township->id,2);
            $township->total_count = $this->get_count($township->id,1) + $this->get_count($township->id,2);
        }
        $teams = Group::orderBy('group_name')->get();
        foreach ($teams as $key => $team) {
            $team->cust_new = $this->get_cust_count($team->id,1);
            $team->cust_left = $this->get_cust_count($team->id,2);
            $team->cust_install = $this->get_cust_count($team->id,3);
            //ticket
            $team->solved = $this->get_ticket_count($team->id,1);
            $team->unsolve = $this->get_ticket_count($team->id,2);
            $team->new = $this->get_ticket_count($team->id,3);
        }

        $setting_color = Setting::first();
        if ($setting_color != null) {
            $bg_color = $setting_color->color;
        }
        else{
            $bg_color = " #009879";
        }

        return view('admin.dashboard.dashboard',compact('townships','teams','bg_color'));
    }

    public function get_count($tsh_id,$survey_type)
    {
        if($survey_type == 1){
            $count = new Survey();
            $count = $count->leftJoin('customers','customers.id','=','surveys.cust_id')
                            ->where('customers.tsh_id',$tsh_id)->where('surveys.survey_type',$survey_type)->where('is_solve',1)->where('archive_status',1)->get()->count();
        }else{
            $count = new Ticket();
            $count = $count->leftJoin('customers','customers.id','=','tickets.cust_id')
                            ->where('customers.tsh_id',$tsh_id)->where('is_solve',1)->get()->count();
        }
        

        return $count;
    }

    public function get_cust_count($team_id,$status)
    {
        if ($status == 1) {
            $count = Assign::where('team_id',$team_id)->where('survey_id','!=',null)->whereDate('assign_date',date('Y-m-d'))->where('is_solve',0)->get()->count();
        }
        if ($status == 2) {
            $count = Assign::where('team_id',$team_id)->where('survey_id','!=',null)->whereDate('assign_date','!=',date('Y-m-d'))->where('is_solve',0)->get()->count();
        }

        if ($status == 3) {
            // $count = Assign::where('team_id',$team_id)->where('survey_id','!=',null)->where('is_solve',1)->get()->count();
            $count = new Assign();
            $count = $count->leftJoin('surveys',function($join){
                            $join->on('assigns.survey_id', '=', 'surveys.id');
                        })
                        ->leftjoin('groups','groups.id','=','assigns.team_id')
                        ->leftjoin('customers','customers.id','=','surveys.cust_id')
                        ->leftjoin('townships','townships.id','=','customers.tsh_id')
                        ->select([
                            'surveys.*',
                            'customers.name',
                            'customers.phone_no',
                            'townships.town_name',
                            'customers.address',
                            'groups.group_name AS group_name',
                            'assigns.assign_date',
                            // 'assigns.is_solve',
                            'assigns.team_id',
                            'assigns.solved_date'
                        ])->where('survey_id','!=',null)->where('surveys.is_solve',1)->where('assigns.team_id',$team_id)->where('surveys.survey_type',1)->get()->count();
        }

        return $count;
        
    }

    public function get_ticket_count($team_id,$status)
    {
        if ($status == 1) {
            $count = TicketAssign::where('team_id',$team_id)->where('ticket_id','!=',null)->where('is_solve',1)->get()->count();
        }
        if ($status == 2) {
            $count = TicketAssign::where('team_id',$team_id)->where('ticket_id','!=',null)->whereDate('assign_date','!=',date('Y-m-d'))->where('is_solve',0)->get()->count();
        }

        if ($status == 3) {
            $count = TicketAssign::where('team_id',$team_id)->where('ticket_id','!=',null)->whereDate('assign_date',date('Y-m-d'))->where('is_solve',0)->get()->count();
        }

        return $count;
    }

    public function daily_dashboard(Request $request)
    {
        $date = $request->from_date ? date('Y-m-d',strtotime($request->from_date)) : date('Y-m-d');
        $to_date = $request->to_date ? date('Y-m-d',strtotime($request->to_date)) : date('Y-m-d');

        $assigns = Assign::where('survey_id','!=',null)->whereBetween('assign_date',[$date,$to_date])->where('is_solve',1)->get();
        
        $new = [];
        foreach ($assigns as $key => $assign) {
            
            $survey_type = Survey::find($assign->survey_id)->survey_type;
            if ($survey_type == 1) {
                array_push($new,$assign);
            }
        }
        $new_count = count($new);

        $services = TicketAssign::where('ticket_id','!=',null)->whereBetween('assign_date',[$date,$to_date])->where('is_solve',1)->get();

        $service_c = [];
        foreach ($services as $key => $service) {
            
            $is_solve = Ticket::find($service->ticket_id)->is_solve;
            if ($is_solve == 1) {
                array_push($service_c,$service);
            }
        }
        $service_count = count($service_c);
        
        //for survey amt
        $survey_list = Amount::where('survey_id','!=',null)->get();
        $survey_amt = 0;
        foreach ($survey_list as $key => $list) {
            $is_solve = Survey::find($list->survey_id) ? Survey::find($list->survey_id)->is_solve : 0;
            $survey_type = Survey::find($list->survey_id) ? Survey::find($list->survey_id)->survey_type : null;
            $assigns = Assign::where('survey_id',$list->survey_id)->first();
            if ($is_solve == 1 && $survey_type == 1 && ($assigns->solved_date >= $date && $assigns->solved_date <= $to_date)) {
                $survey_amt += $list->install_charge + $list->service_charge + $list->cabling_charge + $list->cloud_charge;
            }

        }

        //for ticket amt
        $ticket_list = Amount::where('ticket_id','!=',null)->get();
        $ticket_amt = 0;
        foreach ($ticket_list as $key => $list) {
            $is_solve = Ticket::find($list->ticket_id)->is_solve;
            $assigns = TicketAssign::where('ticket_id',$list->ticket_id)->first();
            if ($is_solve == 1 && ($assigns->solved_date >= $date && $assigns->solved_date <= $to_date)) {
                $ticket_amt += $list->install_charge + $list->service_charge + $list->cabling_charge + $list->cloud_charge;
            }
        }

        //total service fee
        $amt = $survey_amt + $ticket_amt;
      
        $teams = Group::orderBy('group_name')->get();
        // dd($teams);
        foreach ($teams as $key => $team) {
            $team->new_install_count = $this->get_new_count($team->id,$date,$to_date);
            $team->service_count = $this->get_service_count($team->id,$date,$to_date);
            $team->service_charge = $this->get_service_charge($team->id,$date,$to_date);
        }

        // dd($teams);

        $category_list = Category::where('status',1)->where('show_status',1)->get();
        
        foreach ($category_list as $key => $list) {
            $list->qty = $this->get_cat_qty($list->id,$date,$to_date);
        }

        // dd($category_list);

        $team_list = Group::orderBy('group_name')->get();
        foreach ($team_list as $key => $list) {
            $list->cat_list = $this->get_cat_list($list->id,$date,$to_date);
        }

        $setting_color = Setting::first();
        if ($setting_color != null) {
            $bg_color = $setting_color->color;
        }
        else{
            $bg_color = " #009879";
        }


        // dd($team_list);
        return view('admin.dashboard.daily_dashboard',compact('new_count','service_count','amt','teams','category_list','team_list','bg_color'));
    }

    public function get_new_count($team_id,$date,$to_date)
    {
        // dd($status);
       
            $assign_list = Assign::where('survey_id','!=',null)->whereBetween('solved_date',[$date.' 00:00:59',$to_date.' 23:59:59'])->where('solved_by',$team_id)->get();
        
            $new_count = 0;
            foreach ($assign_list as $key => $list) {
                $is_solve = Survey::find($list->survey_id)->is_solve;
                $survey_type = Survey::find($list->survey_id)->survey_type;
                if ($is_solve == 1 && $survey_type == 1) {
                    $new_count += 1;
                }
            }

        return $new_count;
        

    }

    public function get_service_count($team_id,$date,$to_date)
    {
        // dd($team_id);
        $new_count = TicketAssign::whereBetween('solved_date',[$date.' 00:00:59',$to_date.' 23:59:59'])->where('team_id',$team_id)->get()->count();
        // dd($new_count);
        return $new_count;
    }

    public function get_service_charge($team_id,$date,$to_date)
    {
        
        $survey_amt = 0;
        
        $assigns = Assign::where('solved_by',$team_id)->where('is_solve',1)->where('solved_date','!=',null)->get();

        foreach ($assigns as $key => $value) {
            if (($value->solved_date >= $date.' 00:00:59' && $value->solved_date <= $to_date.' 23:59:59')) {
                    $survey_amt += $this->get_total_amt($value->survey_id);
                }
        }
        // return $survey_amt;

        $ticket_list = Amount::where('ticket_id','!=',null)->get();
        // dd($ticket_list);
        $ticket_amt = 0;
        foreach ($ticket_list as $key => $list) {
            $is_solve = Ticket::find($list->ticket_id)->is_solve;
            $assigns = TicketAssign::where('ticket_id',$list->ticket_id)->where('solved_by',$team_id)->first();
            if ($assigns != null) {
                if ($is_solve == 1 && ($assigns->solved_date >= $date && $assigns->solved_date <= $to_date)) {
                    $ticket_amt += $list->install_charge + $list->service_charge + $list->cabling_charge + $list->cloud_charge;
                }
            }
            
        }

        $amt = $survey_amt + $ticket_amt;
        return $amt;
    }

    public function get_total_amt($survey_id)
    {
        $amt = Amount::where('survey_id',$survey_id)->first();
        if ($amt != null) {
            $total_amt = $amt->total_amt;
        }else{
            $total_amt = 0;
        }
        return $total_amt;
    }

    public function get_cat_qty($cat_id,$date,$to_date)
    {
       
        $survey_install_item = SurveyInstallItem::where('cat_id',$cat_id)->where('is_install',1)->get();
        // dd($survey_install_item);
        $qty = 0;
        foreach ($survey_install_item as $key => $item) {
            $t_id = $item->ticket_id == null ? 0 : $item->ticket_id;
            $s_id = $item->survey_id == null ? 0 : $item->survey_id;

            $survey = Assign::where('survey_id',$s_id)->first();
            
            $ticket = Assign::where('ticket_id',$t_id)->first();

            if ($survey != null && $ticket != null) {
                    if (($survey->solved_date >= $date.' 00:00:59' && $surveys->solved_date <= $to_date.' 23:59:59') || ($ticket->solved_date >= $date && $ticket->solved_date <= $to_date)) {
                    $qty += $item->qty;
                }
            }elseif ($survey != null) {
                if ($survey->solved_date >= $date.' 00:00:59' && $survey->solved_date <= $to_date.' 23:59:59') {
                    $qty += $item->qty;
                }
            }elseif ($ticket != null) {
                if ($ticket->solved_date >= $date.' 00:00:59' && $ticket->solved_date<= $to_date.' 23:59:59') {
                    $qty += $item->qty;
                }
            }
            
        }
        return $qty;
    }

    public function get_cat_list($team_id,$date,$to_date)
    {
        $category_list = Category::where('show_status',1)->get();

        foreach ($category_list as $key => $category) {
            $category->qty = $this->get_team_cat_qty($category->id,$date,$to_date,$team_id);
        }
        // dd($category_list);
        return $category_list;
    }

    public function get_team_cat_qty($cat_id,$date,$to_date,$team_id)
    {
        
        $assigns = Assign::where('is_solve',1)->where('team_id',$team_id)->whereBetween('solved_date',[$date.' 00:00:59',$to_date.' 23:59:59'])->get();

        $qty = 0;
        foreach ($assigns as $key => $assign) {
            $qty += $this->get_qty($assign->survey_id,$assign->ticket_id,$cat_id);
        }

      // dd($assigns);
        return $qty;
    }

    public function get_qty($survey_id,$ticket_id,$cat_id)
    {
        $t_id = $ticket_id == null ? 0 : $ticket_id;
        $s_id = $survey_id == null ? 0 : $survey_id;

        $survey_install_item = SurveyInstallItem::where('cat_id',$cat_id)->where('survey_id',$s_id)->where('is_install',1)->get();

        $survey_qty = 0;
        foreach ($survey_install_item as $key => $item) {
            $survey_qty += $item->qty;
        }

        

        $ticket_install_item = SurveyInstallItem::where('cat_id',$cat_id)->where('ticket_id',$t_id)->where('is_install',1)->get();
        // dd($ticket_install_item);
        $ticket_qty = 0;
        foreach ($ticket_install_item as $key => $item) {
            $ticket_qty += $item->qty;
        }

        // dd($ticket_qty);

        $total_qty = $survey_qty + $ticket_qty;
        return $total_qty;

    }

    public function package_dashboard(Request $request)
    {
        $customer_package = DB::table('customer_have_packages')
                            ->select('type', DB::raw('COUNT(*) as count'))
                            ->groupBy('type')
                            ->get();
        $types = ['home','shop','office'];

        $result[] = ['Package','Count'];

        $packages = ["CCTV","Smart Home","mm-link Wifi","Fiber Internet","Computer & Mobile","Electronic"];

        foreach ($packages as $key => $value) {
            $result[++$key] = [$value, $this->get_package_count($value)];
        }

        return view('admin.one_stock_package.dashboard',compact('customer_package','types','result'));
    }

    public function get_package_count($package)
    {
        $count = CustomerHavePackage::where('package',$package)->count();
        return $count;
    }

}
