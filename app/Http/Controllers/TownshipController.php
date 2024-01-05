<?php

namespace App\Http\Controllers;

use App\Township;
use Illuminate\Http\Request;

class TownshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $township = new Township();
        if ($request->town_name != '') {
            $township = $township->where('town_name','like','%'.$request->town_name.'%');
        }
        $count=$township->get()->count();
        $township = $township->orderBy('created_at','desc')->paginate(10);;
        return view('admin.township.index',compact('township','count'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.township.create');
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
            'town_name'=>'required',
            'townshort_name'=>'required',
            'tsh_color'=>'required'
        ];

         $this->validate($request,$rules);
        $township=Township::create([
            'town_name'=> $request->town_name,
            'townshort_name'=> $request->townshort_name,
            'price'=>$request->price,
            'tsh_color'=>$request->tsh_color,
            'tsh_color2'=>$request->tsh_color2
        ]
        );
        return redirect()->route('township.index')->with('success','Township created successfully');;;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Township  $township
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $township=Township::find($id);
        return view('admin.township.show',compact('township'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Township  $township
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $township=Township::find($id);
        return view('admin.township.edit',compact('township'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Township  $township
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $township=township::find($id);
        $township=$township->update($request->all());
       return redirect()->route('township.index')->with('success','Township updated successfully');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Township  $township
     * @return \Illuminate\Http\Response
     */
    public function destroy(Township $township)
    {
        $township->delete();
  
        return redirect()->route('township.index')
                        ->with('success','Township deleted successfully');
    }
}
