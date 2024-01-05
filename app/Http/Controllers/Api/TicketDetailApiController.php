<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ticket;
use App\Customer;
use App\Image;
use App\Inquiry;
use Validator;
use DB;
use File;

class TicketDetailApiController extends Controller
{
	public function get_ticket_detail(Request $request)
	{
		$input = $request->all();
             $rules=[
                'ticket_id' => 'required'
            ];

            $validator = Validator::make($input, $rules);

             if ($validator->fails()) {
                // dd("Here");
                $messages = $validator->messages();
                return response()->json(['message'=>"Valide Error",'status'=>0]);
            }else{
            	$tickets = new Ticket();

            	$tickets = $tickets->leftJoin('inquiries','inquiries.id','tickets.cus_id')->leftJoin('township','township.id','=','inquiries.tsh_id')
            	->select(
            		'tickets.id',
            		'tickets.ticket_issue',
            		'tickets.description',
            		'inquiries.name',
            		'inquiries.ph_no',
            		'township.town_name',
            		'inquiries.address',
            		'inquiries.lat',
            		'inquiries.lng',
            		'tickets.tech_sign',
            		'tickets.cust_sign',
            		'tickets.created_at',
            		'tickets.updated_at',
            		'tickets.remark',
            		'tickets.assign_date'	
            	);
            	$tickets_img = new Image();
                $tickets_img = $tickets_img->leftJoin('inquiries','inquiries.id','=','images.cust_id')
                ->leftJoin('tickets','tickets.id','=','images.ticket_id')->select(
                    'images.img',
                    'images.path'
                );

            	$tickets = $tickets->find($request->ticket_id);
            	// dd($tickets->cus_id);
            	$tickets_img = $tickets_img->where('images.ticket_id',$request->ticket_id)->get();
            	return response()->json(['ticket_detail'=>$tickets,'tickets_img'=>$tickets_img,'message'=>"Success",'status'=>1]);
            }
	}
}