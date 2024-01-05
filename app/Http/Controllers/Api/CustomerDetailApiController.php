<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Inquiry;
use App\InstallItem;
use App\Image;
use Validator;

class CustomerDetailApiController extends Controller
{
	public function get_customer_data(Request $request)
	{
		$input = $request->all();
             $rules=[
                'cust_id' => 'required',
            ];

            $validator = Validator::make($input, $rules);

             if ($validator->fails()) {
                $messages = $validator->messages();
                   return response()->json(['message'=>"Error",'status'=>0]);
            }else{
               $data = new Inquiry();
                $customer_info = $data->leftJoin('township','township.id','=','inquiries.tsh_id')->leftJoin('customers','customers.cust_id','=','inquiries.id')
                    ->select(
                        'inquiries.id',
                        'inquiries.name',
                        'inquiries.ph_no',
                        'township.town_name',
                       	'inquiries.address',
                        'inquiries.lat',
                        'inquiries.lng',
                        'customers.cust_sign',
                        'customers.tech_sign',
                        'customers.created_at',
                        'customers.updated_at',
                        'inquiries.assign_date'
                    );
                    $customer_info = $customer_info->find($request->cust_id);

                $customer_imgs = new Image();
                $customer_imgs = $customer_imgs->leftJoin('inquiries','inquiries.id','=','images.cust_id')->select(
                    'images.img',
                    'images.path'
                );
                $customer_imgs = $customer_imgs->where('images.cust_id',$request->cust_id)->get();

                $items = new InstallItem();
                 $install_items = $items->leftJoin('category','category.id','=','install_items.cat_id')->leftJoin('brand','brand.id','=','install_items.brand_id')->leftJoin('items','items.id','=','install_items.item_id')
                 ->select(
                 	'install_items.id',
                 	'category.name AS cat_name',
                 	'brand.name AS brand_name',
                 	'install_items.model_id',
                 	'install_items.qty',
                 	'install_items.price',
                 	'install_items.amount',
                 	'install_items.serial_no'
                 );

                 $install_items = $install_items->where('install_items.survey_id',$request->cust_id)->get();

                   return response([
                    'customer_info' =>$customer_info,
                    'installed_items'=>$install_items,
                    'customer_imgs'=>$customer_imgs,
                    'message'=>"Success",
                    'status'=>1
                ]);
                }
	}
}