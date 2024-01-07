<?php

namespace App\Http\Controllers;
use App\Survey;
use App\Township;
use App\Group;
use App\Item;
use App\Category;
use App\ServiceCharge;
use App\Assign;
use App\Photo;
use App\SurveyInstallItem;
use App\Amount;
use App\Customer;
use App\Ticket;
use App\TicketAssign;
use App\Signature;
use App\WarrantyPeriod;
use App\CustomerHavePackage;
use DB;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\CustomerExport;
use App\Exports\StockPackageExport;
use App\Imports\CustomerImport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\CustomClasses\ColectionPaginate;
use URL;
use File;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $assigns = new Assign();
        // $assigns = $assigns->leftJoin('surveys',function($join){
        //                     $join->on('assigns.survey_id', '=', 'surveys.id');
        //                 })
        //                 ->leftjoin('groups','groups.id','=','assigns.team_id')
        //                 ->leftjoin('customers','customers.id','=','surveys.cust_id')
        //                 ->leftjoin('townships','townships.id','=','customers.tsh_id')
        //                 ->select([
        //                     'surveys.*',
        //                     'customers.name',
        //                     'customers.phone_no',
        //                     'townships.town_name',
        //                     'customers.address',
        //                     'groups.group_name AS group_name',
        //                     'assigns.assign_date',
        //                     // 'assigns.is_solve',
        //                     'assigns.team_id',
        //                     'assigns.solved_date'
        //                 ])->where('survey_id','!=',null)->where('surveys.is_solve',1);
        // dd($request->all());
        // dd($request->all());
        $assigns = new Survey();
        $assigns = $assigns->leftJoin('customers','customers.id','=','surveys.cust_id')
                            ->leftjoin('townships','townships.id','=','customers.tsh_id')
                            ->select([
                                'surveys.c_code',
                                'surveys.id',
                                'surveys.cust_id',
                                'surveys.lat',
                                'surveys.lng',
                                'customers.name',
                                'customers.phone_no',
                                'townships.town_name',
                                'customers.address',
                                'surveys.is_solve',
                                'surveys.created_at',
                                'surveys.admin_check',
                                'surveys.checked_by'
                            ])->where('surveys.is_solve',1);

        if ($request->keyword != null) {
            $assigns = $assigns->where('customers.name','like','%'.$request->keyword.'%')->orwhere('surveys.c_code','like','%'.$request->keyword.'%');
        }

        if ($request->survey_type != null) {
            $assigns = $assigns->where('survey_type',$request->survey_type);
        }
        if ($request->tsh_id != null) {
            $assigns = $assigns->where('customers.tsh_id',$request->tsh_id);
        }
 
        $dateS = Carbon::now()->startOfMonth()->subMonth(3);
       
        $dateE = Carbon::now()->startOfMonth(); 

        

        // if ($request->assign_status != null) {
        //     $assigns = $assigns->where('assign_status',$request->assign_status);
        // }

        // if ($request->solve_status != null) {
        //     $assigns = $assigns->where('is_solve',$request->solve_status);
        // }

        if ($request->from_date != null && $request->to_date != null) {
            $from_date = date('Y-m-d',strtotime($request->from_date))." 00:00:00";
            $to_date = date('Y-m-d',strtotime($request->to_date))." 23:59:59";

            // dd($from_date,$to_date);
            $assigns = $assigns->whereBetween('surveys.created_at',[$from_date,$to_date]);
        }
        // dd($assigns->get());
        $assigns = $assigns->get()->reverse();
        // dd($assigns[48]);
        // foreach ($assigns as $key => $assign) {
            
        //         $assign->group_name = $this->get_survey_assign_team($assign->cust_id) != null ? $this->get_survey_assign_team($assign->cust_id)->group_name : null;
        //         $assign->assign_date = $this->get_survey_assign_team($assign->cust_id) != null ? $this->get_survey_assign_team($assign->cust_id)->assign_date : null;
        //         $assign->solved_date = $this->get_survey_assign_team($assign->cust_id) != null ? $this->get_survey_assign_team($assign->cust_id)->solved_date:null;
        //         $assign->team_id = $this->get_survey_assign_team($assign->cust_id) != null ? $this->get_survey_assign_team($assign->cust_id)->team_id:null;
          
        // }

        if ($request->team_id != null) {
            $assigns = $assigns->where('team_id',$request->team_id);
        }

        // if ($request->warranty_status != '') {
        //     if ($request->warranty_status == 1) {
        //         $assigns = $assigns->where('assigns.solved_date',[date('Y-m-d',strtotime($dateS))]);
        //     }
            
        // }

        // dd($assigns);


        $count=$assigns->count();
        // $assigns = $assigns->orderBy('surveys.created_at','ASC')->paginate(10);

        $townships = Township::all();
        $teams = Group::orderBy('group_name')->get();

        $warranty_period = WarrantyPeriod::first()->period;

        $assigns = $this->paginate($assigns);

        return view('admin.customer.index',compact('assigns','count','townships','teams','warranty_period'));
    }

    public function paginate($items,$perPage = 10, $page = null, $options = [])
    {
        // dd($items);
        $url = URL::to('/').'/customer?';

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

         return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), 
           $perPage,$page, array('path' => $url));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // dd($id);
        $survey = Survey::find($id);

        // $customer_data = Customer::find($survey->cust_id);
        $customer_data = new Customer();
        $customer_data = $customer_data->leftJoin('townships','townships.id','=','customers.tsh_id')
                                        ->select('customers.*','townships.town_name')
                                        ->find($survey->cust_id);
        // dd($customer_data);
        $assign = new Assign();
        $assign = $assign->leftJoin('groups','groups.id','=','assigns.team_id')
                            ->select('assigns.*','groups.group_name')
                            ->where('survey_id',$id)->get();
                            // dd($assign);
        if (count($assign)==0) {
            $ticket = Ticket::where('cust_id',$survey->cust_id)->first();
            // dd($ticket);
            if($ticket != null){
                $assign->group_name = $this->get_assign_team($ticket->id)->group_name;
                $assign->assign_date = $this->get_assign_team($ticket->id)->assign_date;
                $assign->solved_date = $this->get_assign_team($ticket->id)->solved_date;
            }else{
                $assign->group_name = null;
                $assign->assign_date = null;
                $assign->solved_date = null;
            }
            
        }else{
            $assign = $assign[0];
        }

        // dd($assign);
         
                           
        $tickets = new Ticket();
        $tickets = $tickets->leftJoin('issue_types','issue_types.id','=','tickets.issue_id')
                            ->select('tickets.*','issue_types.issue_type')
                            ->where('cust_id',$id)->get();
        
        foreach ($tickets as $key => $ticket) {
            $ticket->assign_team = $this->get_assign_team($ticket->id)->group_name;
            $ticket->assign_date = $this->get_assign_team($ticket->id)->assign_date;
            $ticket->solve_date = $this->get_assign_team($ticket->id)->solved_date;
        }

        $survey_install_items = new SurveyInstallItem();
        $survey_install_items = $survey_install_items->leftJoin('items','items.id','=','survey_install_items.item_id')->leftjoin('categories','categories.id','=','survey_install_items.cat_id')->leftjoin('brands','brands.id','=','items.brand_id')
        ->select(
            'survey_install_items.*',
            'categories.name',
            'items.model',
            'brands.name AS brand_name'
        )->where('survey_id',$id)->get();
        
        $amounts = Amount::where('survey_id',$id)->first();

        $photos = Photo::where('survey_id',$id)->where('is_survey',1)->get();

         $survey_photos = Photo::where('survey_id',$id)->where('is_survey',0)->get();
        
        $signatures = Signature::where('survey_id',$id)->get();
        // dd($signatures);
        return view('admin.customer.show',compact('customer_data','assign','tickets','survey_install_items','amounts','photos','survey','signatures','survey_photos'));
    }

    public function get_assign_team($id)
    {
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
                            'ticket_assigns.solved_date'
                        ])->where('ticket_assigns.ticket_id',$id)->first();
        // dd($tickets);
        return $tickets;
    }

    public function get_survey_assign_team($id)
    {
        $assigns = new Assign();
        $assigns = $assigns->leftJoin('surveys',function($join){
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
                            'assigns.is_solve',
                            'assigns.team_id',
                            'assigns.solved_date',
                            'surveys.cust_id AS c_id'
                        ])->where('surveys.cust_id',$id)->where('assigns.is_solve',1)->first();
        // dd($assigns);
        if ($assigns == null) {
            // dd("Here");
            $tickets = new Ticket();
            $tickets = $tickets->leftJoin('issue_types','issue_types.id','=','tickets.issue_id')
                                ->select('tickets.*','issue_types.issue_type')
                                ->where('cust_id',$id)->get();
            // dd($tickets[0]);
            if ($tickets->count()>0) {
                $ticket_assigns = new TicketAssign();
                $ticket_assigns = $ticket_assigns->leftJoin('groups','groups.id','=','ticket_assigns.team_id')
                            ->select('ticket_assigns.*','groups.group_name')
                            ->where('ticket_assigns.ticket_id',$tickets[0]->id)->first();
            }else{
                $ticket_assigns = null;
            }
            

            // dd($ticket_assigns);
          return $ticket_assigns;
        }
        // dd($assigns);
        return $assigns;
        // dd($assigns);

        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $signatures = Signature::where('survey_id',$id)->delete();
        // $install_items = SurveyInstallItem::where('survey_id',$id)->delete();
        // $assigns = Assign::where('survey_id',$id)->delete();
        // $photos = Photo::where('survey_id',$id)->delete();
        // $survey = Survey::find($id);
        
        // $survey = Survey::find($id)->delete();
        // $customer = Customer::find(Survey::find($id)->cust_id)->delete();
        // return redirect()->route('customer.index')->with('success','Success');
    }

    public function customer_search(Request $request)
    {
       $data = new Assign();
        $data = $data->leftJoin('surveys',function($join){
                            $join->on('assigns.survey_id', '=', 'surveys.id');
                        })
                        ->leftjoin('customers','customers.id','=','surveys.cust_id')
                        ->leftjoin('groups','groups.id','=','assigns.team_id')
                        ->select([
                            'surveys.*',
                            'customers.name',
                            'customers.phone_no',
                            'groups.group_name',
                            'assigns.assign_date',
                            'assigns.is_solve',
                            'assigns.team_id'
                        ]);
        if($request->has('q')){
            $search = $request->q;
            $data = $data->where('surveys.name','like','%'.$search.'%');
            
        }

        $data = $data->where('surveys.is_solve',1)->get();
        
        return response()->json($data);
    }

    public function print($id)
    {
        $survey = Survey::find($id);
      
        $customer_data = new Customer();
        $customer_data = $customer_data->leftJoin('townships','townships.id','=','customers.tsh_id')
                                        ->select('customers.*','townships.town_name')
                                        ->find($survey->cust_id);
        // dd($customer_data);
        $assign = new Assign();
        $assign = $assign->leftJoin('groups','groups.id','=','assigns.team_id')
                            ->select('assigns.*','groups.group_name','groups.loginId')
                            ->where('survey_id',$id)->first();

        $survey_install_items = new SurveyInstallItem();
        $survey_install_items = $survey_install_items->leftJoin('items','items.id','=','survey_install_items.item_id')->leftjoin('categories','categories.id','=','survey_install_items.cat_id')->leftjoin('brands','brands.id','=','items.brand_id')
        ->select(
            'survey_install_items.*',
            'categories.name',
            'items.model',
            'brands.name AS brand_name'
        )->where('survey_id',$id)->get();
        
        $amounts = Amount::where('survey_id',$id)->first();
        return view('admin.survey.print',compact('survey_install_items','customer_data','assign','amounts'));
    }

    public function customer_map(Request $request)
    {

        $townships = Township::all();
        if ($request->tsh_id != null) {
            $locations = new Survey();
            $locations = $locations->leftJoin('customers','customers.id','=','surveys.cust_id')
                                    ->leftjoin('townships','townships.id','=','customers.tsh_id')
                                    ->select('customers.name','customers.phone_no','townships.town_name','surveys.lat','surveys.lng','customers.tsh_id','customers.address','customers.id')
                                    ->where('is_solve',1);
             if ($request->tsh_id != '') {
                 $locations = $locations->where('customers.tsh_id',$request->tsh_id);
             }
             

             $locations = $locations->get();
        }else{
            $locations = [];
        }
        
        return view('admin.customer.cust_map',compact('locations','townships'));
    }

    public function customer_export(Request $request)
    {
        return Excel::download(new CustomerExport, 'customers.xlsx');
    }

    public function customer_import(Request $request)
    {
       $request->validate([
            'file'=>'required',
        ]);

        Excel::import(new CustomerImport,request()->file('file'));
             
        return back();
    }

    public function download_csv()
    {
        $strpath = public_path().'/uploads/customer.csv';

        $isExists = File::exists($strpath);

        if(!$isExists){
            return redirect()->back()->with('error','File does not exists!');
        }

        $csvFile = str_replace("\\", '/', $strpath);
        $headers = ['Content-Type: application/*'];
        $fileName = 'Customer Demo.csv';

        return response()->download($csvFile, $fileName, $headers);
    }

    public function update_admin_check(Request $request)
    {
        // dd("Here");
        $survey = Survey::find($request->survey_id)->update([
            'admin_check'=>1,
            'checked_by'=>auth()->user()->name
        ]);
        return response()->json(1);
    }

    public function package_customers(Request $request)
    {
        // dd($request->all());
        // $packageName = 'CCTV';
        // $customers = Customer::whereHas('packages', function ($query) use ($packageName) {
        //     $query->where('package', $packageName);
        // })->get();

        // dd($customers);

        $customer_list = new Customer();
        $customer_list = $customer_list->leftJoin('townships','townships.id','=','customers.tsh_id')->select('customers.*','townships.town_name');
        if ($request->keyword != null) {
            $customer_list = $customer_list->where('name','like','%'.$request->keyword.'%');
        }

        if ($request->tsh_id != null) {
            $customer_list = $customer_list->where('tsh_id',$request->tsh_id);
        }

        if ($request->from_date != null && $request->to_date != null) {
            $from_date = date('Y-m-d',strtotime($request->from_date)).' 00:00:00';
            $to_date = date('Y-m-d',strtotime($request->to_date)).' 23:59:59';

            $customer_list = $customer_list->whereBetween('customers.created_at',[$from_date,$to_date]);
        }
      
        if ($request->team_id != null) {
            $customer_list = $customer_list->where('cby',$request->team_id);
        }

        if ($request->package_id != null) {
            $customer_list = $customer_list->whereHas('packages', function ($query) use ($request) {
                            $query->where('package', $request->package_id);
                        });
        }

        if ($request->loc_id != null) {
            $customer_list = $customer_list->whereHas('packages', function ($query) use ($request) {
                            $query->where('type', $request->loc_id);
                        });
        }

        if ($request->package_id != null && $request->loc_id != null) {
            $customer_list = $customer_list->whereHas('packages', function ($query) use ($request) {
                            $query->where('type', $request->loc_id)
                                ->where('package', $request->package_id);
                        });
        }


        $count = $customer_list->where('c_type','package')->count();
        $customer_list = $customer_list->where('c_type','package')->orderBy('customers.created_at','desc')->paginate(10);

        return view('admin.one_stock_package.index',compact('customer_list','count'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function package_cust_detail($id)
    {
        $customer = new Customer();
        $customer = $customer->leftJoin('townships','townships.id','=','customers.tsh_id')->select('customers.*','townships.town_name')->find($id);
        
        $customer_package = DB::table('customer_have_packages')
                            ->select('type', DB::raw('COUNT(*) as count'))
                            ->where('customer_id',$id)
                            ->groupBy('type')
                            ->get();

        return view('admin.one_stock_package.detail',compact('customer_package','customer'));
    }

    public function one_stock_export()
    {
        return Excel::download(new StockPackageExport, 'one_stock_package_customer.xlsx');
    }

    public function package_create()
    {
        return view('admin.one_stock_package.create');
    }

    public function one_stop_store(Request $request)
    {
        $packageList = [];

        foreach ($request->input('install_type') as $installType) {
            $currentPackageList = [
                'install_type' => $installType,
                'package' => $request->input($installType . '_package', [])
            ];

            $packageList[] = $currentPackageList;
        }

        // dd($packageList);

        DB::beginTransaction();
        try {
            $customer = Customer::create([
                'name'=>$request->name,
                'phone_no'=>$request->ph_no,
                'tsh_id'=>$request->tsh_id,
                'address'=>$request->address,
                'lat'=>$request->lat,
                'lng'=>$request->lng,
                'c_type'=>'package',
                'cby'=>auth()->user()->name,
                'remark'=>$request->remark,
                'created_at'=>date('Y-m-d',strtotime($request->created_date)).' '.date('H:i:s')
            ]);

            foreach ($packageList as $key => $value) {
                
                foreach ($value['package'] as $key => $package) {
                    $c_package = CustomerHavePackage::create([
                        'customer_id'=>$customer->id,
                        'type'=>$value['install_type'],
                        'package'=>$package
                    ]);
                }
                
            }
            
            DB::commit();
           return redirect()->route('package_customers')->with('success','Success');
        } catch (Exception $e) {
            
            DB::rollback();
             return redirect()->route('package_customers')->with('error','Error');
        }
    }

    public function package_customer_delete($id)
    {
        DB::beginTransaction();
        try {
            $customer = Customer::find($id);

           $package = CustomerHavePackage::where('customer_id',$id)->delete();

           $customer = $customer->delete();
           DB::commit();
            return redirect()->back()->with('success','Success');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error','Error');
        }
       
    }

    public function package_edit($id) {
        $customer = Customer::find($id);
        $home_packages = CustomerHavePackage::where('customer_id', $id)
                            ->where('type', 'home')
                            ->pluck('package')->toArray();
    
        $shop_packages = CustomerHavePackage::where('customer_id', $id)
        ->where('type', 'shop')
        ->pluck('package')->toArray();

        $office_packages = CustomerHavePackage::where('customer_id', $id)
                            ->where('type', 'office')
                            ->pluck('package')->toArray();
        return view('admin.one_stock_package.edit',compact('customer','home_packages','shop_packages','office_packages'));
    }

    public function one_stop_update($id,Request $request) {
       DB::beginTransaction();
       try {
        $customer = Customer::find($id)->update([
            'name'=>$request->name,
            'phone_no'=>$request->ph_no,
            'tsh_id'=>$request->tsh_id,
            'address'=>$request->address,
            'lat'=>$request->lat,
            'lng'=>$request->lng,
            'remark'=>$request->remark,
            'created_at'=>date('Y-m-d',strtotime($request->created_date)).' '.date('H:i:s')
        ]);
        $packages = CustomerHavePackage::where('customer_id',$id)->delete();

        $packageList = [];

        foreach ($request->input('install_type') as $installType) {
            $currentPackageList = [
                'install_type' => $installType,
                'package' => $request->input($installType . '_package', [])
            ];

            $packageList[] = $currentPackageList;
        }

        foreach ($packageList as $key => $value) {
            
            foreach ($value['package'] as $key => $package) {
                $c_package = CustomerHavePackage::create([
                    'customer_id'=>$id,
                    'type'=>$value['install_type'],
                    'package'=>$package
                ]);
            }
            
        }

        DB::commit();
        return redirect()->route('package_customers')->with('success','Success');
       } catch (Exception $e) {
        DB::rollback();
        return redirect()->route('package_customers')->with('error',$e->getMessage());
       }
    }
}
