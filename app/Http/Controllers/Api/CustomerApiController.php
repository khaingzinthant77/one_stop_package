<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Survey;
use App\Customer;
use App\Assign;
use App\Ticket;
use App\TicketAssign;
use App\CustomerHavePackage;
use Carbon\Carbon;
use DB;
use File;
use Image;
use Validator;

class CustomerApiController extends Controller
{
	public function exist_customer_list(Request $request)
	{
		$input = $request->all();
       	$rules=[
            'page' => 'required',
	      ];
	      $validator = Validator::make($input, $rules);
	      $response = array('response' => '', 'success'=>false);
	       if ($validator->fails()) {
	          $messages = $validator->messages();
	              return response([
                    'message'=>$validator->messages()->first(),
                    'status'=>0,
                ]);
	      }else{
	      	// $assigns = new Assign();
	       //  $assigns = $assigns->leftJoin('surveys',function($join){
	       //                      $join->on('assigns.survey_id', '=', 'surveys.id');
	       //                  })
	       //                  ->leftjoin('groups','groups.id','=','assigns.team_id')
	       //                  ->leftjoin('customers','customers.id','=','surveys.cust_id')
	       //                  ->leftjoin('townships','townships.id','=','customers.tsh_id')
	       //                  ->select([
	       //                      'surveys.*',
	       //                      'customers.name',
	       //                      'customers.phone_no',
	       //                      'townships.town_name',
	       //                      'customers.address',
	       //                      'groups.group_name AS group_name',
	       //                      'assigns.assign_date',
	       //                      'assigns.team_id',
	       //                      'assigns.solved_date'
	       //                  ])->where('surveys.is_solve',1);
	        $assigns = new Survey();
            $assigns = $assigns->leftJoin('customers','customers.id','=','surveys.cust_id')
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
                                    'surveys.created_at',
                                    'surveys.c_code'
                                ])->where('surveys.is_solve',1);

	        if ($request->keyword != null) {
	            $assigns = $assigns->where('customers.name','like','%'.$request->keyword.'%')->orwhere('surveys.c_code','like','%'.$request->keyword.'%')->orwhere('customers.phone_no','like','%'.$request->keyword.'%')->orwhere('surveys.c_code','like','%'.$request->keyword.'%');
	        }

	        $assigns = $assigns->orderBy('surveys.created_at','DESC')->paginate(10);

	        return response([
                    'message'=>"Success",
                    'status'=>1,
                    'customer_list'=>$assigns,
                ]);
          }
	}

	public function customer_detail($id)
	{
		$survey = Survey::find($id);

        // $customer_data = Customer::find($survey->cust_id);
        $customer_data = new Customer();
        $customer_data = $customer_data->leftJoin('townships','townships.id','=','customers.tsh_id')
                                        ->select('customers.*','townships.town_name')
                                        ->find($survey->cust_id);
        $ticket = Ticket::where('cust_id',$survey->cust_id)->first();
        // dd($customer_data);
        $assign = new Assign();
        $assign = $assign->leftJoin('groups','groups.id','=','assigns.team_id')
                            ->select('assigns.*','groups.group_name')
                            ->where('survey_id',$id)->first();

        if ($ticket != null) {
        	$ticket_assign = new TicketAssign();
        	$ticket_assign = $ticket_assign->leftJoin('groups','groups.id','=','ticket_assigns.team_id')
                            ->select('ticket_assigns.*','groups.group_name')
                            ->where('ticket_id',$ticket->id)->first();
        }else{
        	$ticket_assign = null;
        }
        

        // // dd($assign);
        if ($assign != null) {
        	 $customer_data->solve_date = $assign->solved_date;
        }else{
        	 $customer_data->solve_date = null;
        }
       	if ($ticket_assign != null) {
       		$customer_data->ticket_solve_date = $ticket_assign->solved_date;
       	}else{
       		$customer_data->ticket_solve_date = null;
       	}
        
         
        $tickets = new Ticket();
        $tickets = $tickets->leftJoin('issue_types','issue_types.id','=','tickets.issue_id')
                            ->select('tickets.id','tickets.description','issue_types.issue_type','tickets.remark','tickets.is_solve')
                            ->where('cust_id',$id)->where('is_solve',1)->get();

        foreach ($tickets as $key => $ticket) {
        	$ticket->solve_date = $this->get_solve_date($ticket->id);
        }
        
        return response([
                    'message'=>"Success",
                    'status'=>1,
                    'customer_data'=>$customer_data,
                    'ticket_list'=>$tickets,
                    'c_code'=>$survey->c_code
                ]);
	}

	public function get_solve_date($ticket_id)
	{
		$assign = TicketAssign::where('ticket_id',$ticket_id)->first();

		if ($assign != null) {
			return $assign->solved_date;
		}else{
			return null;
		}
	}

	public function package_customer_create(Request $request)
	{
		DB::beginTransaction();
		try {
			$customer = Customer::create([
				'name'=>$request->name,
				'phone_no'=>$request->phone,
				'tsh_id'=>$request->tsh_id,
				'address'=>$request->address,
				'lat'=>$request->lat,
				'lng'=>$request->lng,
				'c_type'=>'package',
				'cby'=>$request->created_by,
				'remark'=>$request->remark
			]);

			foreach ($request->package_list as $key => $value) {
				
				foreach ($value['package_name'] as $key => $package) {
					$c_package = CustomerHavePackage::create([
						'customer_id'=>$customer->id,
						'type'=>$value['install_type'],
						'package'=>$package
					]);
				}
				
			}
			
			DB::commit();
			return response([
                'message'=>"Success",
                'status'=>1,
            ]);
		} catch (Exception $e) {
			
			DB::rollback();
			return response([
                'message'=>"Something wrong!",
                'status'=>0,
            ]);
		}
	}

	public function customer_list(Request $request)
	{
		$customer_list = new Customer();
		$customer_list = $customer_list->leftJoin('townships','townships.id','=','customers.tsh_id')->select('customers.id','customers.name','customers.phone_no','customers.address','customers.lat','customers.lng','townships.town_name')->where('c_type','package');

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

		$customer_list = $customer_list->where('customers.cby',$request->created_by)->orderBy('customers.created_at','DESC')->paginate(10);

		return response([
                    'message'=>"Success",
                    'status'=>1,
                    'customer_list'=>$customer_list,
                ]);
	}


    public function package_cust_detail(Request $request)
    {
        $customer = new Customer();
        $customer = $customer->leftJoin('townships','townships.id','=','customers.tsh_id')->select('customers.id','customers.name','customers.phone_no','customers.address','customers.lat','customers.lng','townships.town_name','customers.tsh_id','customers.cby','customers.remark')->find($request->cust_id);

        $customer_package = DB::table('customer_have_packages')
                            ->select('type', DB::raw('COUNT(*) as count'))
                            ->where('customer_id',$request->cust_id)
                            ->groupBy('type')
                            ->get();
        foreach ($customer_package as $key => $value) {
        	$value->package_list = $this->get_package($value->type,$request->cust_id);
        }

		return response([
                    'message'=>"Success",
                    'status'=>1,
                    'customer_info'=>$customer,
                    'customer_package'=>$customer_package
                ]);

    }

    public function get_package($type,$cust_id)
    {
    	$package = CustomerHavePackage::where('customer_id',$cust_id)->where('type',$type)->get();
    	// dd($package);
    	$package_list = [];
    	foreach ($package as $key => $value) {
    		array_push($package_list, $value->package);
    	}

    	return $package_list;
    }

    public function package_list()
    {
    	$package = ['CCTV','Smart Home','mm-link Wifi','Fiber Internet','Computer & Mobile','Electronic'];

    	return response([
                    'message'=>"Success",
                    'status'=>1,
                    'package_list'=>$package,
                ]);
    }
}