<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Township;
use App\Category;
use App\Brand;
use App\Group;
use App\IssueType;
use App\ServiceCharge;
use App\Item;
use App\WarrantyPeriod;
class MasterApiController extends Controller
{
    /**
    * Survey.
    *
    * @return Response
    */
    public function get_master_data(Request $request)
    {
    	$townships = Township::all();
        $categories = Category::all();
        $brands = Brand::all();
        $cloud_service_charge = ServiceCharge::first()->price;
       
    	return response([
                    'townships' =>$townships,
                    'categories'=>$categories,
                    'brands'=>$brands,
                    'cloud_service_charge'=>$cloud_service_charge,
                    'message'=>"Success",
                    'status'=>1
            ]);
    }

    public function ticket_master_data(Request $request)
    {
        $townships = Township::all();
        $teams = Group::all();
        $issue_types = IssueType::where('status',1)->get();
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

        if ($request->cat_id != '') {
            
            $models = $models->where('items.brand_id',$request->brand_id);
        }

        if ($request->cat_id != '') {
            $models = $models->where('items.cat_id',$request->cat_id);
        }
        $models = $models->get();
        $cloud_service_charge = ServiceCharge::first()->price;
        $warranty = WarrantyPeriod::first();
        // dd($warranty);
        return response([
                    'cloud_service_charge'=>$cloud_service_charge,
                    'townships' =>$townships,
                    'teams'=>$teams,
                    'issue_types'=>$issue_types,
                    'models'=>$models,
                    'warranty_period'=>$warranty->period,
                    'message'=>"Success",
                    'status'=>1
            ]);
    }

    public function townships()
    {
        $townships = Township::all();
        return response([
                    'townships' =>$townships,
                    'message'=>"Success",
                    'status'=>1
            ]);
    }
    public function team_list(Request $request)
    {
        $teams = Group::orderBy('group_name')->get();
        $cloud_service_charge = ServiceCharge::first()->price;
        return response([
                    'teams' =>$teams,
                    'cloud_service_charge'=>$cloud_service_charge,
                    'message'=>"Success",
                    'status'=>1
            ]);
    }
}
