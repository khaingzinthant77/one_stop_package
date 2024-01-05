<?php

namespace App\Http\Controllers;

use App\Brand;
use Illuminate\Http\Request;
use App\Exports\BrandsExport;
use App\Imports\BrandsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Category;
use App\Item;
use File;
use DB;


class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $category = Category::all();
        $brand = new Brand();
        if ($request->name != '') {
            $brand = $brand->where('name','like','%'.$request->name.'%');
        }
        // if ($request->cat_id != '') {
        //     $brand = $category->where('cat_id',$request->cat_id);
        // }
        $count=$brand->get()->count();
        $brand = $brand->orderBy('name','ASC')->paginate(10);

        return view('admin.brand.index',compact('brand','count'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         // $category = Category::all();
          return view('admin.brand.create');
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          $rules = [
            'name'=>'required',
            // 'categorys'=>'required',
        ];

        $this->validate($request,$rules);

        $brand = Brand::create([
                    'name'=> $request->name,
                    // 'cat_id'=>$request->categorys,
                ]);
  
          return redirect()->route('brand.index')
                        ->with('success','Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand=Brand::find($id);
        // $category=Category::all();
        return view('admin.brand.show',compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $category = Category::all();
        $brand = Brand::find($id);

        return view('admin.brand.edit',compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
        $branddata = Brand::findorfail($id);

        $brand = $branddata->update([
                    'name'=> $request->name,
                    // 'cat_id'=>$request->categorys,
        ]);

       return redirect()->route('brand.index')->with('success','Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::where('brand_id',$id)->get();
        if (count($item)>0) {
            return redirect()->route('brand.index')->with('error','Band cannot delete!!!');
        }else{
            $branddata = Brand::find($id);

            $brand = $branddata->delete();
            return redirect()->route('brand.index')->with('success','Successfully');
        }
    }

    public function brandexport() 
    {
        return Excel::download(new BrandsExport, 'brand.xlsx');
    }

    public function brandimport(Request $request) 
    {
        $request->validate([
            'file'=>'required',
        ]);

        Excel::import(new BrandsImport,request()->file('file'));
             
        return back();
    }

    public function search_brand(Request $request)
    {
        $data = new Brand();
        $data = $data->where('status',1);
        if($request->has('q')){
            $search = $request->q;
            $data = $data->where('name','like','%'.$search.'%');
        }
       
        $data = $data->get();
        return response()->json($data);
    }

    public function change_status_brand(Request $request)
    {
        $brand = Brand::find($request->branch_id);
        $brand->status = $request->status;

        $brand->save();
        return response()->json(['success'=>'Success']);
    }

}