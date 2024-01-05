<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Inquiry;
use App\Customer;
use App\Image;
use Validator;
use DB;
use File;

class SaveInstallCustomerApiController extends Controller
{
	public function save_complete_customer(Request $request)
	{
		$input = $request->all();
             $rules=[
                'is_solve' => 'required',
                'img'=>'required',
            ];

            $validator = Validator::make($input, $rules);

             if ($validator->fails()) {
                // dd("Here");
                $messages = $validator->messages();
                   return response()->json(['message'=>"Valide Error",'status'=>0]);
            }else{
                DB::beginTransaction();
                $inquiry_data = Inquiry::find($request->cust_id);

                $filename="img_".date("Y-m-d-H-m-s");
                $path="uploads/customer/".$filename;
 
                if(!File::isDirectory($path)){
                    File::makeDirectory($path, 0777, true, true);
                }

                $inquiry_data = $inquiry_data->update([
                    'is_solve'=>$request->is_solve
                ]);
                 foreach ($request->img as $key => $image) {
                    $filename="img_".date("Y-m-d-H-m-s");
                    $path="uploads/customer/".$filename;
                    // dd($path);
     
                    if(!File::isDirectory($path)){
                        File::makeDirectory($path, 0777, true, true);
                    }
                    $photo = "";
                    //upload image
                    if ($file = $image) {
                        $extension = $file->getClientOriginalExtension();
                        $safeName = str_random('10') . '.' . $extension;
                        $file->move($path, $safeName);
                        $photo = $safeName;
                    }

                    $image = Image::create([
                        'cust_id'=>$request->cust_id,
                        'img'=>$photo,
                        'path'=>$path
                    ]);
                }
                
         try {
             $inquiry_data = Inquiry::find($request->cust_id);
            // $destinationPath = '/uploads/sign/'.$inquiry_data->name.$inquiry_data->ph_no;
            // $cust_sign_photo = "";
            // if ($request->cust_sign){
            //     // dd("Here");
            //     $cust_image = $request->input('cust_sign'); // image base64 encoded
            //     preg_match("/data:image\/(.*?);/",$cust_image,$image_extension); // extract the image extension
            //     $cust_image = preg_replace('/data:image\/(.*?);base64,/','',$image); // remove the type part
            //     $cust_image = str_replace(' ', '+', $cust_image);
            //     $cust_sign_photo = 'cust_'.time() . '.' . $image_extension[1]; //generating unique file name;
            //     // dd($cust_sign_photo);
            //     \File::put( public_path().'/uploads/cust/'.$cust_sign_photo,base64_decode($cust_image));
            // }

             $imageName='';
            if ($request->cust_sign){
                $image = $request->input('cust_sign'); // image base64 encoded
                preg_match("/data:image\/(.*?);/",$image,$image_extension); // extract the image extension
                $image = preg_replace('/data:image\/(.*?);base64,/','',$image); // remove the type part
                $image = str_replace(' ', '+', $image);
                $imageName = 'cust_'.time() . '.' . $image_extension[1]; //generating unique file name;
                \File::put( public_path(). '/uploads/sign/customer/'.$imageName,base64_decode($image));
            }

            $tech_sign_photo = ""; 
            if ($request->tech_sign){
                $tech_image = $request->input('tech_sign'); // image base64 encoded
                preg_match("/data:image\/(.*?);/",$tech_image,$image_extension); // extract the image extension
                $tech_image = preg_replace('/data:image\/(.*?);base64,/','',$tech_image); // remove the type part
                $tech_image = str_replace(' ', '+', $tech_image);
                $tech_sign_photo = 'tech_'.time() . '.' . $image_extension[1]; //generating unique file name;
                // dd($tech_sign_photo);
                \File::put( public_path().'/uploads/sign/technician/'.$tech_sign_photo,base64_decode($tech_image));
            }
            // dd($inquiry_data->tsh_id);
            $customer = Customer::create([
                    'cust_id'=> $request->cust_id,
                    'tsh_id' => $inquiry_data->tsh_id,
                    'cust_sign'=>$imageName,
                    'tech_sign'=>$tech_sign_photo,
        ]);

         DB::commit();
             
         } catch (Exception $e) {
            dd($e);
              DB::rollback();
            return response([
                    'message'=>"Error",
                    'status'=>0
            ]);
         }
          return response([
                    'message'=>"Success",
                    'status'=>1
            ]);
            }
    }
}