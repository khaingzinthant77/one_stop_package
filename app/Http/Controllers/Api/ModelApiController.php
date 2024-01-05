<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Item;
use App\Brand;
use App\Category;
use App\WarrantyPeriod;
use App\ServiceCharge;
use Validator;

class ModelApiController extends Controller
{
    /**
    * Survey.
    *
    * @return Response
    */
    public function get_all_models(Request $request)
    {
        $data = new Item();
        $models = $data->leftJoin('categories','categories.id','=','items.cat_id')
            ->leftJoin('brands','brands.id','=','items.brand_id')
            ->select(
                'items.id',
                'items.model',
                'items.price',
                'categories.id AS cat_id',
                'categories.name AS cat_name',
                'categories.install_charge',
                'brands.id AS brand_id',
                'brands.name AS brand_name'
            ); 

            $cloud_service_charge = ServiceCharge::first()->price;

        if ($request->cat_id != '') {
            
            $models = $models->where('items.brand_id',$request->brand_id);
        }

        if ($request->cat_id != '') {
            $models = $models->where('items.cat_id',$request->cat_id);
        }
        $warranty = WarrantyPeriod::first();
        $models = $models->get();
                return response([
                    'models' =>$models,
                    'warranty_period'=>$warranty->period,
                    'cloud_service_charge'=>$cloud_service_charge,
                    'message'=>"Success",
                    'status'=>1
                ]);  
    }
}
