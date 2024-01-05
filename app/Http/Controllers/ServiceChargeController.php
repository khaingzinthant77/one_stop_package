<?php

namespace App\Http\Controllers;

use App\ServiceCharge;
use Illuminate\Http\Request;

class ServiceChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $servicecharge = new ServiceCharge();
        if ($request->name != '') {
            $servicecharge = $servicecharge->where('name','like','%'.$request->name.'%');
        }
        $count=$servicecharge->get()->count();
        $servicecharge = $servicecharge->orderBy('created_at','desc')->paginate(10);;
        return view('admin.servicecharge.index',compact('servicecharge','count'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.servicecharge.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        $servicecharge=ServiceCharge::create([
            'name'=> $request->name,
            'price'=>$request->price,
        ]
        );
        return redirect()->route('servicecharge.index')->with('success','Success');;;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ServiceCharge  $serviceCharge
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
          $servicecharge=ServiceCharge::find($id);
        return view('admin.servicecharge.show',compact('servicecharge'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServiceCharge  $serviceCharge
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $servicecharge=ServiceCharge::find($id);
        return view('admin.servicecharge.edit',compact('servicecharge'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServiceCharge  $serviceCharge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $servicecharge=ServiceCharge::find($id);
        $servicecharge=$servicecharge->update($request->all());
       return redirect()->route('servicecharge.index')->with('success','Success');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceCharge  $serviceCharge
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
           $servicecharge = ServiceCharge::findorfail($id);
            $servicecharge->delete();
  
        return redirect()->route('servicecharge.index')
                        ->with('success','ServiceCharge deleted successfully');
    }
}
