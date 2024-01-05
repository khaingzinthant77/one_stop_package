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

class SolvedTicketApiController extends Controller
{
	public function solve_ticket(Request $request)
	{
		$input = $request->all();
             $rules=[
                'ticket_id' => 'required',
                'is_solve'=>'required'
            ]; 

            $validator = Validator::make($input, $rules);

             if ($validator->fails()) {
                // dd("Here");
                $messages = $validator->messages();
                return response()->json(['message'=>"Valide Error",'status'=>0]);
            }else{
            	$ticket = Ticket::find($request->ticket_id);
            	// dd($ticket->cus_id);
            // 	$cust_sign_photo = "";
            // if ($request->cust_sign){
            //     $image = $request->input('cust_sign'); // image base64 encoded
            //     preg_match("/data:image\/(.*?);/",$image,$image_extension); // extract the image extension
            //     $image = preg_replace('/data:image\/(.*?);base64,/','',$image); // remove the type part
            //     $image = str_replace(' ', '+', $image);
            //     $cust_sign_photo = time() . '.' . $image_extension[1]; //generating unique file name;
            //     // dd($cust_sign_photo);
            //     \File::put( public_path().'/uploads/sign/'.$cust_sign_photo,base64_decode($image));
            // } 

             $imageName='';
            if ($request->cust_sign){
                $image = $request->input('cust_sign'); // image base64 encoded
                preg_match("/data:image\/(.*?);/",$image,$image_extension); // extract the image extension
                $image = preg_replace('/data:image\/(.*?);base64,/','',$image); // remove the type part
                $image = str_replace(' ', '+', $image);
                $imageName = 'cust_'.time() . '.' . $image_extension[1]; //generating unique file name;
                \File::put( public_path(). '/uploads/sign/customer_ticket/'.$imageName,base64_decode($image));
            }

            $tech_sign_photo = ""; 
            if ($request->tech_sign){
                $image = $request->input('tech_sign'); // image base64 encoded
                preg_match("/data:image\/(.*?);/",$image,$image_extension); // extract the image extension
                $image = preg_replace('/data:image\/(.*?);base64,/','',$image); // remove the type part
                $image = str_replace(' ', '+', $image);
                $tech_sign_photo = 'tech_'.time() . '.' . $image_extension[1]; //generating unique file name;
                \File::put( public_path().'/uploads/sign/technician_ticket/'.$tech_sign_photo,base64_decode($image));
            }
            if ($request->img != null) {
            	// dd($request->img);
            	foreach ($request->img as $key => $image) {
            		// dd($image);
                    $filename="img_".date("Y-m-d-H-m-s");
                    $path="uploads/customer/".$filename;
                    // dd($path);
     
                    if(!File::isDirectory($path)){
                        File::makeDirectory($path, 0777, true, true);
                    }
                    $photo = "";
                    //upload image
                    if ($file = $image) {
                    	// dd("Here");
                        $extension = $file->getClientOriginalExtension();
                        $safeName = str_random('10') . '.' . $extension;
                        $file->move($path, $safeName);
                        $photo = $safeName;
                    }

                    $image = Image::create([
                        'cust_id'=>$ticket->cus_id,
                        'ticket_id'=>$request->ticket_id,
                        'img'=>$photo,
                        'path'=>$path
                    ]);
                    // dd($image);
                }
            }
            $ticket = $ticket->update([
            	'solved'=>$request->is_solve,
            	'cust_sign'=>$imageName,
            	'tech_sign'=>$tech_sign_photo,
            	'remark'=>$request->remark
            ]);
            return response()->json(['message'=>"Success",'status'=>1]);
            }
    }
}