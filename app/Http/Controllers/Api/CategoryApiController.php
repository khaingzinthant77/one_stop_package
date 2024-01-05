<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;

class CategoryApiController extends Controller
{
    /**
    * Survey.
    *
    * @return Response
    */
    public function get_all_categories(Request $request)
    {
    	$categories = Category::all();
    	return response([
                    'categories' =>$categories,
                    'message'=>"Success",
                    'status'=>1
            ]);
    }
}
