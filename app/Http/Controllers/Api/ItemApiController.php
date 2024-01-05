<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Item;

class ItemApiController extends Controller
{
    /**
    * Survey.
    *
    * @return Response
    */
    public function get_all_items(Request $request)
    {
    	$items = Item::all();
    	return response([
                    'items' =>$items,
                    'message'=>"Success",
                    'status'=>1
            ]);
    }
}
