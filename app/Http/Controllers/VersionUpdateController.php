<?php

namespace App\Http\Controllers;

use App\VersionUpdate;
use Illuminate\Http\Request;

class VersionUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $versions = VersionUpdate::all();
        return view('admin.version.index',compact('versions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.version.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $rules = [
            'v_code'=>'required',
            'v_name'=>'required',
            'd_url'=>'required'
        ];

        $this->validate($request,$rules);

        $version = VersionUpdate::create([
            'vCode'=>$request->v_code,
            'vName'=>$request->v_name,
            'direct_url'=>$request->d_url
        ]);

        return redirect()->route('version_update.index')->with('success','Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VersionUpdate  $versionUpdate
     * @return \Illuminate\Http\Response
     */
    public function show(VersionUpdate $versionUpdate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VersionUpdate  $versionUpdate
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $version = VersionUpdate::find($id);

        return view('admin.version.edit',compact('version'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VersionUpdate  $versionUpdate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'v_code'=>'required',
            'v_name'=>'required',
            'd_url'=>'required'
        ];

        $this->validate($request,$rules);

        $version = VersionUpdate::findorFail($id);

        $version = $version->update([
            'vName'=>$request->v_name,
            'vCode'=>$request->v_code,
            'direct_url'=>$request->d_url
        ]);

        return redirect()->route('version_update.index')->with('success','Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VersionUpdate  $versionUpdate
     * @return \Illuminate\Http\Response
     */
    public function destroy(VersionUpdate $versionUpdate)
    {
        //
    }
}
