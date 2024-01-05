<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use Validator;
use DB;
use File;
use App\Group;
use App\Township;
use App\Survey;
use App\Assign;
use App\SurveyInstallItem;
use App\Photo;
use App\Amount;
use App\Signature;
use App\Item;
use App\IssueType;
use App\Customer;
use App\TicketAssign;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketExport;
use App\Exports\TicketReportExport;

class TicketController extends Controller
{
	public function index(Request $request)
	{
		$groups = Group::orderBy('group_name')->get();
		$townships = Township::all();
		// $tickets = new Ticket();
		// $tickets = $tickets->leftJoin('surveys','surveys.id','=','tickets.cust_id')
		// 					->leftjoin()
		// 					->select(
		// 						'surveys.*',
		// 						'tickets.ticket_issue',
		// 						'tickets.description',
		// 						'tickets.is_solve'
		// 					);
		// 					dd($tickets->get());
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
							'townships.townshort_name',
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
                           	'ticket_assigns.checked_by',
                           	// 'tickets.ticket_ID'
                        ]);
        if ($request->keyword != null) {
        	$tickets = $tickets->where('customers.name','like','%'.$request->keyword.'%');
        }
        
        if($request->is_solve != null){
        	$tickets = $tickets->where('ticket_assigns.is_solve',$request->is_solve);
        }

        if ($request->team_id != null) {
        	$tickets = $tickets->where('ticket_assigns.team_id',$request->team_id);
        }

        if ($request->issue_id != null) {
        	$tickets = $tickets->where('tickets.issue_id',$request->issue_id);
        }

        if ($request->from_date != null && $request->to_date != null) {

        	$from_date = date('Y-m-d',strtotime($request->from_date)).' 00:00:59';
        	$to_date = date('Y-m-d',strtotime($request->to_date)).' 23:59:59';
        	
        	$tickets = $tickets->whereBetween('ticket_assigns.solved_date',[$from_date,$to_date]);
        }

        if ($request->tsh_id != null) {
        	$tickets = $tickets->where('customers.tsh_id',$request->tsh_id);
        }



        $issue_types = IssueType::where('status',1)->get();
        
		$count = $tickets->get()->count();
		$tickets = $tickets->orderBy('tickets.created_at','desc')->paginate(10);
		
		return view('admin.ticket.index',compact('groups','townships','tickets','count','issue_types'));
	}

	public function create()
	{
		$customers = new Assign();
        $customers = $customers->leftJoin('surveys',function($join){
                            $join->on('assigns.survey_id', '=', 'surveys.id');
                        })
                        ->leftjoin('groups','groups.id','=','assigns.team_id')
                        ->select([
                            'surveys.*',
                            'groups.group_name',
                            'assigns.assign_date',
                            'assigns.is_solve',
                            'assigns.team_id'
                        ])->where('assigns.is_solve',1)->get();
        $groups = Group::orderBy('group_name')->get();
        $townships = Township::all();
        $ticket_issues = IssueType::where('status',1)->get();
        return view('admin.ticket.create',compact('customers','groups','townships','ticket_issues'));
	}

	public function store(Request $request)
	{
		// dd(Ticket::latest()->first()->ticket_ID);

       $input = $request->all();
        $rules=[
            'c_type'=>'required',
        ];
          $validator = Validator::make($input, $rules);
          $hour = date('H:i:s');
           if ($validator->passes()) {

           	// $ticket_count = Ticket::where('ticket_id','!=',null)->get()->count();

			// if ($ticket_count > 0) {
			// 	$ticket = str_pad(++$ticket_count, 3, '0', STR_PAD_LEFT);
			// 	$ticket_ID = date('ym').$ticket;
			// }else{
			// 	$ticket = str_pad(1, 3, '0', STR_PAD_LEFT);
			// 	$ticket_ID = date('ym').$ticket;
			// }

			// dd($ticket_ID);

           	//linn customer
           	if ($request->c_type == 1) {
           		$tsh_id = Customer::find($request->cust_id)->tsh_id;
           		$tsh_name = Township::find($tsh_id)->townshort_name;

           		$tickets = new TicketAssign();
        		$ticket_count = $tickets->leftJoin('tickets',function($join){
                            $join->on('ticket_assigns.ticket_id', '=', 'tickets.id');
                        })
                        ->leftjoin('groups','groups.id','=','ticket_assigns.team_id')
                        ->leftJoin('surveys','surveys.id','=','tickets.cust_id')
                        ->leftjoin('customers','customers.id','=','surveys.cust_id')
                        ->leftjoin('townships','townships.id','=','customers.tsh_id')
                        ->leftjoin('issue_types','issue_types.id','=','tickets.issue_id')->where('customers.tsh_id',$tsh_id)->get()->count();
                 // dd($tsh_name);

           		$rules=[
		            'cust_id'=>'required',
		            'team_id'=>'required',
		            'ticket_issue'=>'required',
		            'description'=>'required'
		        ];
		         $validator = Validator::make($input, $rules);
		         if ($validator->passes()) {
		         		$ticket = Ticket::create([
		         			// 'ticket_ID'=>$ticket_ID,
			         		'cust_id'=>$request->cust_id,
			         		'issue_id'=>$request->ticket_issue,
			         		'description'=>$request->description,
			         		'is_solve'=>0,
			         	]);
			         	$assigns = TicketAssign::create([
			         		'ticket_id'=>$ticket->id,
			         		'team_id'=>$request->team_id,
			         		'assign_date'=>date('Y-m-d',strtotime($request->assign_date)).' '.$hour,
			         		'is_solve'=>0
			         	]);
		         	
		         }else{
		         	return redirect()->route('ticket.index')->with('error','Something wrong!');
		         }
           	}else{

           		$rules=[
		            'c_name'=>'required',
		            'phone_no'=>'required',
		            'tsh_id'=>'required',
		            'address'=>'required',
		            'team_id'=>'required',
		            'ticket_issue'=>'required',
		            'description'=>'required'
		        ];
		         $validator = Validator::make($input, $rules);
		         if ($validator->passes()) {

		         	// DB::beginTransaction();
		         	// try {

		         		// $surveys = Survey::where('is_solve',1)->get();
		         		$surveys = new Survey();
		         		$surveys = $surveys->leftJoin('customers','customers.id','=','surveys.cust_id')->where('surveys.is_solve',1)->where('customers.tsh_id',$request->tsh_id)->get();
	                    $count = $surveys->count();
	                    $cust_count = str_pad(++$count,4,"0",STR_PAD_LEFT);
	                    $tsh_short_code = Township::find($request->tsh_id)->townshort_name;
	                    $voucher_no = $tsh_short_code.$cust_count;
	                    // dd($voucher_no);
	                    // $
	                    $customer = Customer::create([
	                    	'name'=>$request->c_name,
			         		'phone_no'=>$request->phone_no,
			         		'tsh_id'=>$request->tsh_id,
			         		'address'=>$request->address,
	                    ]);
			         	$survey = Survey::create([
			         		'cust_id'=>$customer->id,
			         		'assign_status'=>1,
			         		'survey_by'=>auth()->user()->id,
			         		'survey_name'=>auth()->user()->name,
			         		'lat'=>$request->lat,
			         		'lng'=>$request->lng,
			         		'is_solve'=>1,
			         		'c_code'=>$voucher_no,
			         		'survey_type'=>2

			         	]);
			         	$ticket = Ticket::create([
			         		// 'ticket_ID'=>$ticket_ID,
			         		'cust_id'=>$survey->id,
			         		'issue_id'=>$request->ticket_issue,
			         		'description'=>$request->description,
			         		'is_solve'=>0
			         	]);
			         	$assign = Assign::create([
			         		// 'ticket_id'=>$ticket->id,
			         		'survey_id'=>$survey->id,
			         		'team_id'=>$request->team_id,
			         		'assign_date'=>date('Y-m-d',strtotime($request->assign_date)).' '.$hour,
			         		'is_solve'=>1,
			         		'solved_date'=>date('Y-m-d'),
			         		'solved_by'=>$request->team_id
			         	]);

			         	$assigns = TicketAssign::create([
			         		'ticket_id'=>$ticket->id,
			         		'team_id'=>$request->team_id,
			         		'assign_date'=>date('Y-m-d',strtotime($request->assign_date)).' '.$hour,
			         		'is_solve'=>0
			         	]);
			         	
			         	return redirect()->route('ticket.index')->with('success','Success');
		         	// } catch (Exception $e) {
		         	// 	return redirect()->route('ticket.index')->with('error','Something wrong!');
		         	// }
		         	
		         }else{
		         	return redirect()->route('ticket.index')->with('error','Something wrong!');
		         }
           	}
           }else{
           	return redirect()->route('ticket.index')->with('error','Something wrong!');
           }
           return redirect()->route('ticket.index')->with('success','Success');
	}

	public function show($id)
	{
		// dd($id);
		// $tickets = Ticket::find($id);
		// dd($tickets);
		$tickets = new Ticket();
		$tickets = $tickets->leftJoin('surveys','surveys.id','=','tickets.cust_id')
							->leftjoin('customers','customers.id','=','surveys.cust_id')
							->leftjoin('townships','townships.id','=','customers.tsh_id')
							->leftjoin('ticket_assigns',function($join){
								$join->on('ticket_assigns.ticket_id', '=', 'tickets.id');
							})
							->leftjoin('groups','groups.id','=','ticket_assigns.team_id')
							->leftjoin('issue_types','issue_types.id','=','tickets.issue_id')
							->select(
								'customers.name',
								'customers.phone_no',
								'townships.town_name',
								'customers.address',
								'surveys.lat',
								'surveys.lng',
								'tickets.*',
								'issue_types.issue_type',
								'ticket_assigns.team_id',
								'groups.group_name',
								'ticket_assigns.assign_date'
							)->find($id);
		$ticket_install_items = new SurveyInstallItem();

        $ticket_install_items = $ticket_install_items->leftJoin('items','items.id','survey_install_items.item_id')->leftjoin('categories','categories.id','=','survey_install_items.cat_id')
        ->select([
            'survey_install_items.*',
            'items.id AS item_id',
            'items.model',
            'items.model',
            // 'survey_install_items.qty',
            // 'survey_install_items.amount',
            'items.unit',
            'survey_install_items.item_price',
            'categories.install_charge AS price'
        ])->where('ticket_id',$id)->get();
        
		$ticket_photos = Photo::where('ticket_id',$id)->get();
		$signatures = Signature::where('ticket_id',$id)->get();

		$amounts = Amount::where('ticket_id',$id)->first();
		// dd($amounts);
		return view('admin.ticket.show',compact('tickets','ticket_install_items','ticket_photos','amounts','signatures'));
	}

	public function edit($id)
	{
		$tickets = new Ticket();
		$tickets = $tickets->leftJoin('surveys','surveys.id','=','tickets.cust_id')
							->leftjoin('customers','customers.id','=','surveys.cust_id')
							->leftjoin('townships','townships.id','=','customers.tsh_id')
							->leftjoin('ticket_assigns',function($join){
								$join->on('ticket_assigns.ticket_id', '=', 'tickets.id');
							})
							->leftjoin('groups','groups.id','=','ticket_assigns.team_id')
							->leftjoin('issue_types','issue_types.id','=','tickets.issue_id')
							->select(
								'customers.name',
								'customers.phone_no',
								'townships.town_name',
								'customers.address',
								'customers.tsh_id',
								'surveys.lat',
								'surveys.lng',
								'tickets.*',
								'ticket_assigns.team_id',
								'groups.group_name',
								'ticket_assigns.assign_date'
							)->find($id);
		// dd()
		// $ticket_install_items = SurveyInstallItem::where('ticket_id',$id)->get();
		$ticket_install_items = new SurveyInstallItem();

        $ticket_install_items = $ticket_install_items->leftJoin('items','items.id','survey_install_items.item_id')->leftjoin('categories','categories.id','=','survey_install_items.cat_id')
        ->select([
            'survey_install_items.*',
            'items.id AS item_id',
            'items.model',
            'items.model',
            'survey_install_items.qty',
            'survey_install_items.amount',
            'items.unit',
            'survey_install_items.item_price',
            'categories.install_charge AS price'
        ])->where('ticket_id',$id)->get();
        $ticket_issues = IssueType::where('status',1)->get();
		$ticket_photos = Photo::where('ticket_id',$id)->get();
		$amounts = Amount::where('ticket_id',$id)->first();
		$items = Item::where('status',1)->get();
		$townships = Township::all();
		$teams = new Group();
        $teams = $teams->orderBy('group_name')->get();
        $assign = new TicketAssign();
        $assign = $assign->leftJoin('groups','groups.id','=','ticket_assigns.team_id')
                        ->select([
                            'groups.id',
                            'groups.group_name',
                            'ticket_assigns.assign_date',
                            'ticket_assigns.appoint_date'
                        ])->where('ticket_id',$id)->first();
		return view('admin.ticket.edit',compact('tickets','ticket_install_items','ticket_photos','amounts','items','teams','assign','ticket_issues','townships'));
	}

	public function  update(Request $request,$id)
	{
		

		$input = $request->all();
		// dd($input);
        $rules=[
            'name'=>'required',
            'ph_no'=>'required',
            'tsh_id'=>'required',
            'address'=>'required',
            // 'sub_total'=>'required',
            // 'install_charge'=>'required',
            // 'total_amt'=>'required',
        ];
          $validator = Validator::make($input, $rules);
          $hour = date('H:i:s');
           if ($validator->passes()) {
              DB::beginTransaction();
              try {
                  	$tickets = Ticket::find($id)->update([
                  		'issue_id'=>$request->issue_id,
                  		'description'=>$request->description,
                  		'remark'=>$request->remark
                  	]);
                    $assign = Assign::where('ticket_id',$id)->first();

                    if ($request->team_id != null && $assign == null) {
                        $assign = Assign::create([
                        'ticket_id'=>$id,
                        'team_id'  => $request->team_id,
                        'assign_date'=>date('Y-m-d',strtotime($request->assign_date)).' '.$hour,
                        'appoint_date'=>date('Y-m-d',strtotime($request->assign_date))
                      ]);
                    }else{
                        $assign = $assign->update([
                            'team_id'  => $request->team_id,
                            'assign_date'=>date('Y-m-d',strtotime($request->assign_date)).' '.$hour,
                            'appoint_date'=>date('Y-m-d',strtotime($request->assign_date))
                        ]);
                    }
                    

                  // Photo
                  $date = Carbon::now();
                    $timeInMilliseconds = $date->getPreciseTimestamp(3);
                   
                    $destinationPath = public_path() . '/uploads/ticket/';
                    $photo = "";

                    if ($request->images != null) {
                        foreach ($request->images as $key => $img) {
                            if ($file = $img) {
                            $extension = $file->getClientOriginalExtension();
                            $safeName = 'img'.$timeInMilliseconds.$key.'.' . $extension;
                            $file->move($destinationPath, $safeName);
                            $photo = $safeName;
                        }

                            $photos = Photo::create([
                                'ticket_id'=>$id,
                                'path'=>'uploads/ticket/',
                                'photo_name'=>$photo
                            ]);    

                        }
                    }
                    $survey_install_items = SurveyInstallItem::where('ticket_id',$id)->get()->count();
                    if ($survey_install_items != 0) {
                    	// dd($request->total_amt);
                    	if ($request->total_amt != null || $request->total_amt != 0) {
                    		$survey_install_items = SurveyInstallItem::where('ticket_id',$id)->delete();
	                        foreach ($request->actual_item as $key => $value) {
	                            $survey_install_item = SurveyInstallItem::create([
	                            'ticket_id'=>$id,
	                            'item_id'=>$request->install_amt[$key],
	                            'cat_id'=>$request->cat_id[$key],
	                            'cat_price'=>$request->cat_price[$key],
	                            'qty'=>$request->actual_qty[$key],
	                            'item_price'=>$request->price[$key],
	                            'amount'=>$request->amount[$key],
	                        ]);
	                    }

	                    $total_amt = Amount::where('ticket_id',$id)->first();

	                    if ($total_amt != null) {
	                            $total_amt = $total_amt->update([
	                            'sub_total'=>$request->sub_total,
	                            'total_amt'=>$request->total_amt,
	                            'install_charge'=>$request->install_charge,
	                            'is_cloud'=>$request->cloud_charge == null ? 0 : 1,
	                            'cloud_charge'=>$request->cloud_charge == null ? 0 : $request->cloud_charge,
	                            'service_charge'=>$request->service_charge,
	                            'on_call_charge'=>$request->on_call_charge,
	                            'discount'=>$request->discount == null ? 0 : $request->discount
	                        ]);
	                    }else{
	                        // dd($request->all());
	                        $total_amt = Amount::create([
	                            'ticket_id'=>$id,
	                            'sub_total'=>$request->sub_total,
	                            'total_amt'=>$request->total_amt,
	                            'install_charge'=>$request->install_charge,
	                            'is_cloud'=>$request->cloud_charge == null ? 0 : 1,
	                            'cloud_charge'=>$request->cloud_charge == null ? 0 : $request->cloud_charge,
	                            'service_charge'=>$request->service_charge,
	                            'on_call_charge'=>$request->on_call_charge,
	                            'discount'=>$request->discount == null ? 0 : $request->discount
	                        ]);
	                    }
                    	}
                    }else{
                    	$total_amt = Amount::where('ticket_id',$id)->first();
	                    // dd($total_amt);
	                    if ($total_amt != null) {
	                    	$total_amt = $total_amt->update([
	                            'sub_total'=>$request->sub_total,
	                            'total_amt'=>$request->total_amt,
	                            'install_charge'=>$request->install_charge,
	                            'is_cloud'=>$request->cloud_charge == null ? 0 : 1,
	                            'cloud_charge'=>$request->cloud_charge == null ? 0 : $request->cloud_charge,
	                            'service_charge'=>$request->service_charge,
	                            'on_call_charge'=>$request->on_call_charge,
	                            'discount'=>$request->discount == null ? 0 : $request->discount
	                        ]);
	                    }else{
	                    	$amt  = Amount::create([
                    		'ticket_id'=>$id,
                    		'sub_total'=>0,
                    		'service_charge'=>$request->service_charge,
                    		'on_call_charge'=>$request->on_call_charge,
                    		'discount'=>$request->discount == null ? 0 : $request->discount,
                    		'total_amt'=>$request->total_amt == null ? 0 : $request->total_amt,
                    		'install_charge'=>0
                    	]);
	                    }
                    	
                }

                    
                    
                
                DB::commit();
              } catch (Exception $e) {
              	dd($e);
                  DB::rollback();
                  return redirect()->route('ticket.index')->with('error','Something wrong!');
              }
          }else{
          	// dd("HElo");
            return redirect()->route('ticket.index')->with('error','Something wrong!');
          }
        return redirect()->route('ticket.index')->with('success','Success');
	}

	public function update_ticket_check(Request $request)
	{
		$assigns = TicketAssign::where('ticket_id',$request->ticket_id)->update([
            'admin_check'=>1,
            'checked_by'=>auth()->user()->name
        ]);

        // dd($assigns);
        return response()->json(1);
	}

	public function service_export()
	{
		return Excel::download(new TicketExport, 'service_ticket.xlsx');
	}

	public function ticket_report(Request $request)
	{
		// dd($request->all());
		$amounts = new Amount();
		$amounts = $amounts->leftJoin('tickets','tickets.id','=','amounts.ticket_id')
							->leftjoin('surveys','surveys.id','=','tickets.cust_id')
							->leftjoin('customers', 'customers.id', '=', 'surveys.cust_id')
							->leftjoin('issue_types','issue_types.id','=','tickets.issue_id')
							->where('tickets.is_solve',1)->where('amounts.ticket_id','!=',null)
							->select('amounts.*','tickets.description','customers.name','customers.phone_no','issue_types.issue_type');
		if ($request->keyword != null) {
			$amounts = $amounts->where('customers.name','like','%'.$request->keyword.'%');
		}

		$ticket_ids = [];
		if ($request->team_id != null) {
			$ticket_assign = TicketAssign::where('team_id',$request->team_id)->where('is_solve',1)->select('ticket_id')->get();
			foreach ($ticket_assign as $key => $value) {
				array_push($ticket_ids, $value->ticket_id);
			}

			// dd($ticket_ids);
			$amounts = $amounts->whereIn('amounts.ticket_id',$ticket_ids)->where('customers.name','like','%'.$request->keyword.'%');
		}

		$solved_ids = [];
		if ($request->from_date != null && $request->to_date != null) {
			$from_date = date('Y-m-d',strtotime($request->from_date)).' 00:59:59';
			$to_date = date('Y-m-d',strtotime($request->to_date)).' 23:59:59';

			$ticket_solved = TicketAssign::whereBetween('solved_date',[$from_date,$to_date])->where('is_solve',1)->select('ticket_id')->get();

			foreach ($ticket_solved as $key => $value) {
				array_push($solved_ids, $value->ticket_id);
			}

			// dd($ticket_ids);
			$amounts = $amounts->whereIn('amounts.ticket_id',$solved_ids)->where('customers.name','like','%'.$request->keyword.'%');
		}
		
		$all_amount = $amounts->get();
		$count = $amounts->get()->count();
		$amounts = $amounts->orderBy('tickets.created_at','desc')->paginate(15);

		return view('admin.ticket.report',compact('amounts','count','all_amount'))->with('i', (request()->input('page', 1) - 1) * 15);
	}

	public function service_report_export()
	{
		return Excel::download(new TicketReportExport, 'ticket_report.xlsx');
	}
}