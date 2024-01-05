<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Inquiry;
use Validator;

class NewCustomerApiController extends Controller
{
    /**
    * Survey.
    *
    * @return Response
    */
    public function get_new_customers(Request $request)
    {
       $input = $request->all();
             $rules=[
                'group_id' => 'required',
                'page'=>'required',
            ];

            $validator = Validator::make($input, $rules);

             if ($validator->fails()) {
                $messages = $validator->messages();
                   return response()->json(['message'=>"Error",'status'=>0]);
            }else{
                if ($request->page != 0) {
                    $new_customers = new Inquiry();
                    if ($request->keyword != '') {
                        $new_customers = $new_customers->where('name',$request->keyword)->orwhere('ph_no',$request->keyword);
                    }
                    $new_customers = $new_customers->where('assign_date','!=',null)->where('is_solve',0)->where('group_id',$request->group_id)->where('assign_date','=',date('d-m-yy'))->orderBy('id','desc')->limit(10)->paginate(10);
                        return response([
                                    'new_customers' =>$new_customers,
                                    'message'=>"Success",
                                    'status'=>1
                            ]);
                }else{
                    $new_customers = new Inquiry();
                    if ($request->keyword != '') {
                        $new_customers = $new_customers->where('name',$request->keyword)->orwhere('ph_no',$request->keyword);
                    }
                    $new_customers = $new_customers->where('assign_date','!=',null)->where('is_solve',0)->where('group_id',$request->group_id)->where('assign_date','=',date('d-m-yy'))->orderBy('id','desc')->get();
        
                    return response([
                                'new_customers' =>$new_customers,
                                'message'=>"Success",
                                'status'=>1
                        ]);
                }
                
            }
    	
    }

    public function get_left_customers(Request $request)
    {
        $input = $request->all();
             $rules=[
                'group_id' => 'required',
                'page'=>'required',
            ];

            $validator = Validator::make($input, $rules);

             if ($validator->fails()) {
                $messages = $validator->messages();
                   return response()->json(['message'=>"Error",'status'=>0]);
            }else{
                if ($request->page != 0) {
                    $left_customers = new Inquiry();
                    if ($request->keyword != '') {
                        $left_customers = $left_customers->where('name',$request->keyword)->orwhere('ph_no',$request->keyword);
                    }
                   $left_customers = $left_customers->where('assign_date','!=',null)->where('is_solve',0)->where('group_id',$request->group_id)->where('assign_date','!=',date('d-m-yy'))->orderBy('id','desc')->limit(10)->paginate(10);
        
                return response([
                    'left_customers' =>$left_customers,
                    'message'=>"Success",
                    'status'=>1
                ]);
                }else{
                    $left_customers = new Inquiry();
                    if ($request->keyword != '') {
                        $left_customers = $left_customers->where('name',$request->keyword)->orwhere('ph_no',$request->keyword);
                    }
                    $left_customers = $left_customers->where('assign_date','!=',null)->where('is_solve',0)->where('group_id',$request->group_id)->where('assign_date','!=',date('d-m-yy'))->orderBy('id','desc')->get();
        
                return response([
                    'left_customers' =>$left_customers,
                    'message'=>"Success",
                    'status'=>1
                ]);
                }
                
            }
        
    }

    public function get_total_customer(Request $request)
    {
        $input = $request->all();
             $rules=[
                'group_id' => 'required',
                'page'=>'required',
            ];

            $validator = Validator::make($input, $rules);

             if ($validator->fails()) {
                $messages = $validator->messages();
                   return response()->json(['message'=>"Error",'status'=>0]);
            }else{
                if ($request->page != 0) {
                    $total_customer = new Inquiry();
                    if ($request->start_date != '' && $request->end_date != '') {
                        $startDate = date('Y-m-d', strtotime($request->start_date))." 00:00:00";
                        $endDate = date('Y-m-d', strtotime($request->end_date))." 23:59:59";
                        $total_customer = $total_customer->whereBetween('updated_at',[$startDate, $endDate]);
                    }
                    if ($request->keyword != '') {
                        $total_customer = $total_customer->where('name',$request->keyword)->orwhere('ph_no',$request->keyword);
                    }

                    $total_customer = $total_customer->where('assign_date','!=',null)->where('is_solve',1)->where('group_id',$request->group_id)->orderBy('id','desc')->limit(10)->paginate(10);
                return response([
                    'total_customer' =>$total_customer,
                    'message'=>"Success",
                    'status'=>1
                ]);
                }else{
                    $total_customer = new Inquiry();
                    if ($request->keyword != '') {
                        $total_customer = $total_customer->where('name',$request->keyword)->orwhere('ph_no',$request->keyword);
                    }
                    $total_customer = $total_customer->where('assign_date','!=',null)->where('is_solve',1)->where('group_id',$request->group_id)->orderBy('id','desc')->get();
                return response([
                    'total_customer' =>$total_customer,
                    'message'=>"Success",
                    'status'=>1
                ]);
                }
                
            }
    }
}
