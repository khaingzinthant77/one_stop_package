<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class FrontendController extends Controller
{
	public function home()
	{
		return view('frontend.index');
	}
}