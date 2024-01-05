<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('admin.setting.index',compact('setting'));
    }

    public function create(Request $request)
    {
        $setting = Setting::create([
            'color'=>$request->color
        ]);

        return redirect()->route('setting.index')->with('success','Success');
    }

    public function update($id,Request $request)
    {
        $setting = Setting::find($id)->update([
            'color'=>$request->color
        ]);
        return redirect()->route('setting.index')->with('success','Success');
    }
}
