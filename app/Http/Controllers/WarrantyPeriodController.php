<?php

namespace App\Http\Controllers;

use App\WarrantyPeriod;
use Illuminate\Http\Request;

class WarrantyPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $warranty_periods = new WarrantyPeriod();

        
        $count=$warranty_periods->get()->count();
        $warranty_periods = $warranty_periods->orderBy('period','DESC')->paginate(10);

        return view('admin.warranty_period.index',compact('warranty_periods','count'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'period'=>'required',
        ];

        $this->validate($request,$rules);

        $warranty_period = WarrantyPeriod::create([
                    'period'=> $request->period,
                ]);
  
          return redirect()->route('warranty_period.index')
                        ->with('success','Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WarrantyPeriod  $warrantyPeriod
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $warranty_period = WarrantyPeriod::find($id);
        return view('admin.warranty_period.show',compact('warranty_period'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WarrantyPeriod  $warrantyPeriod
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $warranty_period = WarrantyPeriod::find($id);
        return view('admin.warranty_period.edit',compact('warranty_period'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WarrantyPeriod  $warrantyPeriod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $rules = [
            'period'=>'required',
        ];

        $this->validate($request,$rules);

        $warranty_period = WarrantyPeriod::find($id)->update([
            'period'=>$request->period
        ]);
        return redirect()->route('warranty_period.index')
                        ->with('success','Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WarrantyPeriod  $warrantyPeriod
     * @return \Illuminate\Http\Response
     */
    public function destroy(WarrantyPeriod $warrantyPeriod)
    {
        //
    }
}
