<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\VersionUpdate;

class VersionUpdateApiController extends Controller
{
	public function version_update(Request $request)
	{
		$versions = VersionUpdate::all();
		$version = $versions[0];
		return response([
                'version' =>$version,
                'message'=>"Success",
                'status'=>1
        ]);
	}
}