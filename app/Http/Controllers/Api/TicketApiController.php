<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ticket;
use App\Assign;
use App\SurveyInstallItem;
use App\Photo;
use App\Township;
use App\Amount;
use App\Customer;
use App\Survey;
use App\Signature;
use App\Group;
use App\User;
use App\IssueType;
use App\TicketAssign;
use Validator;
use Carbon\Carbon;
use Image;
use File;

class TicketApiController extends Controller
{
	public function get_new_ticket(Request $request)
	{
		$input = $request->all();
             $rules=[
                'group_id' => 'required',
                'page'=>'required'
            ];

            $validator = Validator::make($input, $rules);

             if ($validator->fails()) {
                $messages = $validator->messages();
                   return response()->json(['message'=>"Error",'status'=>0]);
            }else{
            	if ($request->page != 0) {
                    $new_tickets = new Ticket();
                    $new_ticket = $new_tickets->leftJoin('inquiries','inquiries.id','=','tickets.cus_id')
                    ->select(
                        'tickets.id',
                        'inquiries.name',
                        'inquiries.ph_no',
                        'tickets.ticket_issue',
                        'tickets.description',
                        'tickets.assign_date',
                        'inquiries.address',
                        'inquiries.lat',
                        'inquiries.lng'
                    );

                     if ($request->start_date != '' && $request->end_date != '') {
                        $startDate = date('Y-m-d', strtotime($request->start_date))." 00:00:00";
                        $endDate = date('Y-m-d', strtotime($request->end_date))." 23:59:59";
                        $new_ticket = $new_ticket->whereBetween('tickets.updated_at',[$startDate, $endDate]);
                    }
                    if ($request->keyword != '') {
                        $new_ticket = $new_ticket->where('inquiries.name',$request->keyword)->orwhere('inquiries.ph_no',$request->keyword);
                    }
            		$new_ticket = $new_ticket->where('tickets.assign_date','!=',null)->where('tickets.solved',0)->where('tickets.group_id',$request->group_id)->orderBy('tickets.id','desc')->limit(10)->paginate(10);

	            	return response([
	                    'new_tickets'=>$new_ticket,
	                    'message'=>"Success",
	                    'status'=>1
	            	]);
            	}else{
                    $new_tickets = new Ticket();
                    $new_ticket = $new_tickets->leftJoin('inquiries','inquiries.id','=','tickets.cus_id')
                    ->select(
                        'tickets.id',
                        'inquiries.name',
                        'inquiries.ph_no',
                        'tickets.ticket_issue',
                        'tickets.description',
                        'tickets.assign_date',
                        'inquiries.address',
                        'inquiries.lat',
                        'inquiries.lng'
                    );
                    if ($request->keyword != '') {
                        $new_ticket = $new_ticket->where('inquiries.name',$request->keyword)->orwhere('inquiries.ph_no',$request->keyword);
                    }
            		$new_ticket = $new_ticket->where('tickets.assign_date','!=',null)->where('tickets.solved',0)->where('group_id',$request->group_id)->orderBy('tickets.id','desc')->get();

	            	return response([
	                    'new_tickets'=>$new_ticket,
	                    'message'=>"Success",
	                    'status'=>1
	            	]);
            	}
            	
            }
	}

	public function solved_ticket(Request $request)
    {
        $input = $request->all();
           $rules=[
                'is_solve'=>'required',
                'ticket_id'=>'required',
                'solved_by'=>'required',
                'sub_total'=>'required',
                'total_amt'=>'required',
          ];
          $validator = Validator::make($input, $rules);
          $response = array('response' => '', 'success'=>false);
           if ($validator->fails()) {
              $messages = $validator->messages();
                  // $response['response'] = $validator->messages()->first();
                  // return $response;
              return response([
                'message'=>$validator->messages()->first(),
                'status'=>0,
                
            ]);

          }else{
            $ticket = Ticket::find($request->ticket_id)->update([
                'is_solve'=>0
            ]);
         
            $hour = date('H:i:s');
            $assigns = TicketAssign::where('ticket_id',$request->ticket_id)->update([
                'is_solve'=>0,
            ]);

            $survey_install_items = SurveyInstallItem::where('ticket_id',$request->ticket_id)->get();

            if (count($survey_install_items) > 0) {
                $survey_install_items = SurveyInstallItem::where('ticket_id',$request->ticket_id)->delete();
            }

            $amounts = Amount::where('ticket_id',$request->ticket_id)->first();
            if ($amounts != null) {
                $amounts = Amount::where('ticket_id',$request->ticket_id)->delete();
            }

            

            if (count($request->item)>0) {
                foreach ($request->item as $key => $value) {
                    $install_items = SurveyInstallItem::create([
                        'ticket_id'=>$request->ticket_id,
                        'item_id'=>$value['id'],
                        'item_price'=>$value['price'],
                        'cat_id'=>$value['cat_id'],
                        'qty'=>$value['qty'],
                        'amount'=>$value['amount'],
                        'is_install'=>1
                    ]);
                }
            }
            

            $amount = Amount::create([
                    'ticket_id'=>$request->ticket_id,
                    'total_amt'=>$request->total_amt,
                    'sub_total'=>$request->sub_total,
                    'install_charge'=>$request->install_charge,
                    'is_cloud'=>$request->is_cloud,
                    'cloud_charge'=>$request->cloud_charge,
                    'discount'=>$request->discount,
                    'cabling_charge'=>$request->cabling_charge,
                    'service_charge'=>$request->service_charge,
                    'on_call_charge'=>$request->on_call_charge
                ]);
            

            return response([
                'message'=>"Success",
                'status'=>1,
                'ticket_id'=>$request->ticket_id
            ]);
          }
    }

    public function unsolve_list(Request $request)
    {
        $input = $request->all();
           $rules=[
                'team_id'=>'required',
                'page'=>'required',
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
                            'tickets.id',
                            'customers.id AS cust_id',
                            'customers.name',
                            'customers.phone_no',
                            'townships.town_name',
                            'customers.address',
                            'surveys.lat',
                            'surveys.lng',
                            'ticket_assigns.assign_date',
                            'issue_types.id AS issue_type_id',
                            'issue_types.issue_type',
                            'tickets.description'
                        ])->where('ticket_id','!=',null)->where('ticket_assigns.is_solve',0)->where('ticket_assigns.team_id',$request->team_id);
                                
            if ($request->keyword != null) {
                $assigns = $assigns->where('customers.name','like','%'.$request->keyword.'%')->orwhere('customers.phone_no','like','%'.$request->keyword.'%');
            }

            // if ($request->from_date != null && $request->to_date != null) {
            //     $from_date = date('Y-m-d',strtotime($request->from_date))." 00:00:00";
            //     $to_date = date('Y-m-d',strtotime($request->to_date))." 23:59:59";

            //     // dd($from_date,$to_date);
            //     $tickets = $tickets->whereBetween('ticket_assigns.assign_date',[$from_date,$to_date]);
            // }
            
            $tickets = $tickets->orderBy('surveys.created_at','DESC')->paginate(10);
            return response([
                'message'=>"Success",
                'status'=>1,
                'tickets'=>$tickets
            ]);
          }
    }

    public function solve_list(Request $request)
    {
           $input = $request->all();
           $rules=[
                'team_id'=>'required',
                'page'=>'required',
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
            // dd(date('H:i:s'));
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
                            'tickets.id',
                            'customers.name',
                            'customers.phone_no',
                            'townships.town_name',
                            'customers.address',
                            'surveys.lat',
                            'surveys.lng',
                            'ticket_assigns.solved_date',
                            'ticket_assigns.admin_check',
                            'ticket_assigns.checked_by',
                            'issue_types.issue_type',
                            'tickets.description'
                        ])->where('solved_date','!=',null)->where('ticket_assigns.is_solve',1)->where('ticket_assigns.team_id',$request->team_id);
                         
            if ($request->keyword != null) {
                $tickets = $tickets->where('customers.name','like','%'.$request->keyword.'%')->orwhere('customers.phone_no','like','%'.$request->keyword.'%');
            }

            if ($request->from_date != null && $request->to_date != null) {
                $from_date = date('Y-m-d',strtotime($request->from_date)).' 00:00:59';
                $to_date = date('Y-m-d',strtotime($request->to_date)).' 23:59:59';

                // dd($from_date,$to_date);
                $tickets = $tickets->whereBetween('ticket_assigns.solved_date',[$from_date,$to_date]);
            }
            $tickets = $tickets->orderBy('surveys.created_at','DESC')->paginate(10);
            return response([
                'message'=>"Success",
                'status'=>1,
                'tickets'=>$tickets
            ]);
          }
    }

    public function ticket_detail($id)
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
                                'surveys.lat',
                                'surveys.lng',
                                'issue_types.issue_type',
                                'tickets.*',
                                'ticket_assigns.team_id',
                                'groups.group_name',
                                'ticket_assigns.assign_date',
                                'ticket_assigns.solved_date'
                            )->find($id);
        $ticket_install_items = new SurveyInstallItem();

        $ticket_install_items = $ticket_install_items->leftJoin('items','items.id','survey_install_items.item_id')->leftjoin('categories','categories.id','=','survey_install_items.cat_id')->leftjoin('brands','brands.id','=','items.brand_id')
        ->select([
            // 'survey_install_items.*',
            // 'items.id AS item_id',
            // 'items.model',
            // 'items.model',
            // 'survey_install_items.qty',
            // 'survey_install_items.amount',
            // 'items.unit',
            // 'survey_install_items.item_price',
            // 'categories.install_charge AS price' 
            'items.model',
            'items.unit',
            'survey_install_items.qty',
            'survey_install_items.item_price',
            'survey_install_items.amount',
            'categories.name AS cat_name',
            'brands.name AS brand_name',
            
        ])->where('ticket_id',$id)->get();
        
        $ticket_photos = Photo::where('ticket_id',$id)->get();
        $amounts = Amount::where('ticket_id',$id)->first();
        $signatures = Signature::where('ticket_id',$id)->first();
        return response([
                'message'=>"Success",
                'status'=>1,
                'data'=>$tickets,
                'photo'=>$ticket_photos,
                'install_items'=>$ticket_install_items,
                'amounts'=>$amounts,
                'signatures'=>$signatures
            ]);

    }

    public function other_create(Request $request)
    {
           $input = $request->all();
           $rules=[
                'c_name'=>'required',
                'c_phone'=>'required',
                'tsh_id'=>'required',
                'address'=>'required',
                'lat'=>'required',
                'lng'=>'required',
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
            if ($request->cust_id == null) {
                 $surveys = new Survey();
                    $surveys = $surveys->leftJoin('customers','customers.id','=','surveys.cust_id')->where('surveys.is_solve',1)->where('customers.tsh_id',$request->tsh_id)->get();
                    $count = $surveys->count();
                    $cust_count = str_pad(++$count,4,"0",STR_PAD_LEFT);
                    $tsh_short_code = Township::find($request->tsh_id)->townshort_name;
                    $voucher_no = $tsh_short_code.$cust_count;
                    
                    $customer = Customer::create([
                        'name'=>$request->c_name,
                        'phone_no'=>$request->c_phone,
                        'tsh_id'=>$request->tsh_id,
                        'address'=>$request->address,
                    ]);
                    // $survey_name = Group::find($request->assign_id)->group_name;
                    $user = User::where('group_id',$request->assign_id)->first();
                    $surveys= Group::find($request->assign_id);
                    if ($surveys != null) {
                        $survey_name = $surveys->group_name;
                    }else{
                        $survey_name = $user->name;
                    }

                    $survey = Survey::create([
                        'cust_id'=>$customer->id,
                        'assign_status'=>1,
                        'survey_by'=>$request->assign_id,
                        'survey_name'=>$survey_name,
                        'lat'=>$request->lat,
                        'lng'=>$request->lng,
                        'is_solve'=>1,
                        'c_code'=>$voucher_no,
                        'survey_type'=>2
                    ]);

                    return response([
                        'message'=>"Success",
                        'status'=>1,
                        'cust_id'=>$survey->id
                    ]);
            }else{

                $survey = Survey::find($request->cust_id)->update([
                        'lat'=>$request->lat,
                        'lng'=>$request->lng,
                        'assign_status'=>1,
                        'remark'=>$request->remark
                    ]);

                    $cust_id = Survey::find($request->cust_id)->cust_id;
                    $customer = Customer::find($cust_id)->update([
                        'name'=>$request->c_name,
                        'phone_no'=>$request->c_phone,
                        'tsh_id'=>$request->tsh_id,
                        'address'=>$request->address,
                    ]);
                return response([
                        'message'=>"Success",
                        'status'=>1,
                        'cust_id'=>$request->cust_id
                    ]);
            }
           

          }

        

    }

    public function ticket_create(Request $request)
    {
        $input = $request->all();
           $rules=[
                'cust_id'=>'required',
                'team_id'=>'required',
                'issue_id'=>'required',
                'description'=>'required',
                'assign_date'=>'required',
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
            if ($request->ticket_id == null) {
                $ticket = Ticket::create([
                    'cust_id'=>$request->cust_id,
                    'issue_id'=>$request->issue_id,
                    'description'=>$request->description,
                    'is_solve'=>0,
                    'remark'=>$request->remark
                ]);
               
                $hour = date('H:i:s');
                $assign = TicketAssign::create([
                    'ticket_id'=>$ticket->id,
                    'team_id'=>$request->team_id,
                    'assign_date'=>date('Y-m-d',strtotime($request->assign_date)).' '.$hour,
                    'is_solve'=>0
                ]);
                return response([
                    'message'=>"Success",
                    'status'=>1,
                    'ticket_id'=>$ticket->id
                ]);
            }else{
                    $ticket = Ticket::find($request->ticket_id)->update([
                        'cust_id'=>$request->cust_id,
                        'issue_id'=>$request->issue_id,
                        'description'=>$request->description,
                        'is_solve'=>0,
                        'remark'=>$request->remark
                    ]);
                    $hour = date('H:i:s');
                    // $assign = TicketAssign::create([
                    //     'ticket_id'=>$ticket->id,
                    //     'team_id'=>$request->team_id,
                    //     'assign_date'=>date('Y-m-d',strtotime($request->assign_date)).' '.$hour,
                    //     'is_solve'=>0
                    // ]);
                    $assigns = TicketAssign::where('ticket_id',$request->ticket_id)->first();
                    $assigns = $assigns->update([
                        'team_id'=>$request->team_id,
                        'assign_date'=>date('Y-m-d',strtotime($request->assign_date)).' '.$hour,
                        'is_solve'=>0
                    ]);

                        return response([
                        'message'=>"Success",
                        'status'=>1,
                        'ticket_id'=>$request->ticket_id
                    ]);
                }
            }
    }

    public function issue_type()
    {
        $issue_type = IssueType::where('status',1)->get();
        return response([
                        'message'=>"Success",
                        'status'=>1,
                        'issue_types'=>$issue_type
                    ]);
    }

    public function ticket_aggrement(Request $request)
    {
        $input = $request->all();
           $rules=[
                'ticket_id'=>'required',
                'photos'=>'required',
                'cust_sign'=>'required',
                'tech_sign'=>'required',
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
            $tickets = Ticket::find($request->ticket_id)->update([
                "is_solve"=>1
            ]); 

            $hour = date('H:i:s');
            $assigns = TicketAssign::where('ticket_id',$request->ticket_id)->update([
                'solved_date'=>date('Y-m-d').' '.$hour,
                'is_solve'=>1,
                'solved_by'=>$request->solved_by
            ]);
            
                // $imageName='';
                // if ($request->cust_sign){
                //     $image = $request->input('cust_sign'); // image base64 encoded
                //     preg_match("/data:image\/(.*?);/",$image,$image_extension); // extract the image extension
                //     $image = preg_replace('/data:image\/(.*?);base64,/','',$image); // remove the type part
                //     $image = str_replace(' ', '+', $image);
                //     $imageName = 'cust_'.time() . '.' . $image_extension[1]; //generating unique file name;
                //     \File::put( public_path(). '/uploads/ticket_sign/'.$imageName,base64_decode($image));
                // }

                // $tech_sign_photo = ""; 
                // if ($request->tech_sign){
                //     $tech_image = $request->input('tech_sign'); // image base64 encoded
                //     preg_match("/data:image\/(.*?);/",$tech_image,$image_extension); // extract the image extension
                //     $tech_image = preg_replace('/data:image\/(.*?);base64,/','',$tech_image); // remove the type part
                //     $tech_image = str_replace(' ', '+', $tech_image);
                //     $tech_sign_photo = 'tech_'.time() . '.' . $image_extension[1]; //generating unique file name;
                //     // dd($tech_sign_photo);
                //     \File::put( public_path().'/uploads/ticket_sign/'.$tech_sign_photo,base64_decode($tech_image));
                // }   
                    $date = Carbon::now();
                    $timeInMilliseconds = $date->getPreciseTimestamp(3);
           
                    $destinationPath = public_path() . '/uploads/ticket_sign/';

                    $cust_sign = "";
                        //upload image
                    if ($file = $request->file('cust_sign')) {
                        
                        $extension = $file->getClientOriginalExtension();

                        $input['imagename'] = 'cust_sign'.$timeInMilliseconds.'.' . $extension;
                        $img = Image::make($request->file('cust_sign')->getRealPath());
                        $img->orientate()
                            ->fit(800, 800, function ($constraint) {
                                $constraint->upsize();
                            })->save($destinationPath.'/'.$input['imagename']);
                        $cust_sign = $input['imagename'];
                    }

                    $tech_sign = "";
                        //upload image
                    if ($file = $request->file('tech_sign')) {
                        
                        $extension = $file->getClientOriginalExtension();

                        $input['imagename'] = 'tech_sign'.$timeInMilliseconds.'.' . $extension;
                        $img = Image::make($request->file('tech_sign')->getRealPath());
                        $img->orientate()
                            ->fit(800, 800, function ($constraint) {
                                $constraint->upsize();
                            })->save($destinationPath.'/'.$input['imagename']);
                        $tech_sign = $input['imagename'];
                    }

                    $cust_sign_image = "";
                        //upload image
                    if ($file = $request->file('cust_sign_image')) {
                        
                        $extension = $file->getClientOriginalExtension();

                        $input['imagename'] = 'cust_sign_image'.$timeInMilliseconds.'.' . $extension;
                        $img = Image::make($request->file('cust_sign_image')->getRealPath());
                        $img->orientate()
                            ->fit(800, 800, function ($constraint) {
                                $constraint->upsize();
                            })->save($destinationPath.'/'.$input['imagename']);
                        $cust_sign_image = $input['imagename'];
                    }

                    $tech_sign_image = "";
                        //upload image
                    if ($file = $request->file('tech_sign_image')) {
                        
                        $extension = $file->getClientOriginalExtension();

                        $input['imagename'] = 'tech_sign_image'.$timeInMilliseconds.'.' . $extension;
                        $img = Image::make($request->file('tech_sign_image')->getRealPath());
                        $img->orientate()
                            ->fit(800, 800, function ($constraint) {
                                $constraint->upsize();
                            })->save($destinationPath.'/'.$input['imagename']);
                        $tech_sign_image = $input['imagename'];
                    }


                $signature = Signature::create([
                    'ticket_id'=> $request->ticket_id,
                    'path' => '/uploads/ticket_sign/',
                    'cust_sign'=>$cust_sign,
                    'tech_sign'=>$tech_sign,
                    'cust_sign_image'=>$cust_sign_image,
                    'tech_sign_image'=>$tech_sign_image
                ]);

               
                if ($request->photos != null) {
                    
                    foreach ($request->photos as $key => $image) {
                        // dd($image);
                        $date = Carbon::now();
                        $timeInMilliseconds = $date->getPreciseTimestamp(3);
               
                        $destinationPath = public_path() . '/uploads/ticket/';

                        if(!File::isDirectory($destinationPath)){
                            File::makeDirectory($destinationPath, 0777, true, true);
                        }
                        $photo = "";
                        //upload image
                        if ($file = $image) {
                            
                            $extension = $file->getClientOriginalExtension();

                            $input['imagename'] = 'img'.$timeInMilliseconds.$key.'.' . $extension;
                            $img = Image::make($image->getRealPath());
                            $img->orientate()
                                ->fit(800, 800, function ($constraint) {
                                    $constraint->upsize();
                                })->save($destinationPath.'/'.$input['imagename']);
                            $photo = $input['imagename'];
                        }

                        $image = Photo::create([
                            'ticket_id'=>$request->ticket_id,
                            'photo_name'=>$photo,
                            'path'=>'uploads/ticket/',
                        ]);
                      
                        }
                    }
            return response([
                        'message'=>"Success",
                        'status'=>1,
                    ]);

          }
    }

    public function issue_detail(Request $request)
    {
        // $ticket = Ticket::find($request->ticket_id);
        $ticket = new Ticket();
        $ticket = $ticket->leftJoin('issue_types','issue_types.id','=','tickets.issue_id')
                            ->select('tickets.*','issue_types.issue_type')
                            ->find($request->ticket_id);
        if ($ticket != null) {
            return response([
                        'message'=>"Success",
                        'status'=>1,
                        'detail'=>$ticket
                    ]);
        }else{
            return response([
                        'message'=>"Id does not exit",
                        'status'=>0
                        
                    ]);
        }
        
    }

    public function unsolve_detail(Request $request)
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
                    'tickets.id',
                    'surveys.id AS survey_id',
                    'customers.id AS cust_id',
                    'customers.name',
                    'customers.phone_no',
                    'townships.town_name',
                    'customers.address',
                    'surveys.lat',
                    'surveys.lng',
                    'ticket_assigns.assign_date',
                    'groups.group_name',
                    'issue_types.id AS issue_type_id',
                    'issue_types.issue_type',
                    'tickets.description',
                    'surveys.survey_type'
                ])->where('ticket_id',$request->ticket_id)->first();
                // dd($tickets);
        $survey = Survey::where('cust_id',$tickets->cust_id)->first();

        $assigns = Assign::where('survey_id',$survey->id)->first();
        
        if ($assigns != null) {
            $tickets->install_date = $assigns->solved_date;
        }

        $ticket_list = new TicketAssign();
        $ticket_list = $ticket_list->leftJoin('tickets',function($join){
                    $join->on('ticket_assigns.ticket_id', '=', 'tickets.id');
                })
               
                ->leftjoin('issue_types','issue_types.id','=','tickets.issue_id')
                ->select([
                    
                    'issue_types.issue_type',
                    'tickets.description',
                    'ticket_assigns.solved_date'

                ])->where('tickets.cust_id',$survey->cust_id)->where('ticket_assigns.is_solve',1)->get();

        return response([
                        'message'=>"Success",
                        'detail'=>$tickets,
                        'ticket_list'=>$ticket_list,
                        'status'=>1
                        
                    ]);
    }

    public function admin_solved_ticket(Request $request)
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
                            'ticket_assigns.solved_date',
                            'ticket_assigns.admin_check',
                            'ticket_assigns.checked_by'
                        ])->where('tickets.is_solve',1);

        if ($request->keyword != null) {
            $tickets = $tickets->where('customers.name','like','%'.$request->keyword.'%');
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

        $tickets = $tickets->orderBy('ticket_assigns.solved_date','DESC')->paginate(10);

        return response([
                'message'=>"Success",
                'status'=>1,
                'install_list'=>$tickets
            ]);
    }

    public function ticket_install_items(Request $request)
    {
        $install_items = new SurveyInstallItem();
        $install_items = $install_items->leftJoin('items','items.id','=','survey_install_items.item_id')
                                        ->leftjoin('categories','categories.id','=','survey_install_items.cat_id')->leftjoin('brands','brands.id','=','items.brand_id')
                                        ->select([
                                            'items.model',
                                            'categories.name AS cat_name',
                                            'brands.name AS brand_name',
                                            'survey_install_items.item_price',
                                            'categories.install_charge',
                                            'survey_install_items.qty'
                                        ])->where('survey_install_items.ticket_id',$request->ticket_id)->get();
        $amounts = Amount::where('ticket_id',$request->ticket_id)->first();

        return response([
                'message'=>"Success",
                'status'=>1,
                'install_items'=>$install_items,
                'amount'=>$amounts
            ]);
    }

}