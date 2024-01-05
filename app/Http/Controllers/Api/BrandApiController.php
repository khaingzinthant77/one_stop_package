<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Brand;

class BrandApiController extends Controller
{
    /**
    * Survey.
    *
    * @return Response
    */
    public function get_all_brands(Request $request)
    {
    	$brands = Brand::all();
    	return response([
                    'brands' =>$brands,
                    'message'=>"Success",
                    'status'=>1
            ]);
    }
}
