<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Exports\CategorysExport;
use App\Imports\CategorysImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Brand;
use File;
use DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $category = new Category();
        if ($request->name != '') {
            $category = $category->where('name','like','%'.$request->name.'%');
        }
        $count=$category->get()->count();
        $category = $category->orderBy('created_at','desc')->paginate(10);;
        return view('admin.category.index',compact('category','count'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin.category.create');
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
        ];

         $this->validate($request,$rules);
        $category=Category::create([
            'name'=> $request->name,
            'install_charge'=> $request->price,
        ]
        );
        return redirect()->route('category.index')->with('success','Success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $category=Category::find($id);
        return view('admin.category.show',compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $category=Category::find($id);
         // dd($category);
        return view('admin.category.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        // dd($request->all());
         $category=Category::find($id);
        $category=$category->update([
            'name'=> $request->name,
            'install_charge'=> $request->price,
        ]);
       return redirect()->route('category.index')->with('success','Success');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
         $category->delete();
  
        return redirect()->route('category.index')
                        ->with('success','Success');
    }

    public function categoryexport() 
    {
        return Excel::download(new CategorysExport, 'category.xlsx');
    }

    public function categoryimport(Request $request) 
    {
        $request->validate([
            'file'=>'required',
        ]);

        Excel::import(new CategorysImport,request()->file('file'));
             
        return back();
    }

    public function selectcategory(Request $request)
    {
        if($request->ajax()){
            $brand = Brand::where('cat_id',$request->categorys)->get();
            echo "<option value=''>Select Brand</opiton>";
            foreach ($brand as $key => $sec) {
                echo "<option value='".$sec->id."'>".$sec->name."</opiton>";
            }
        }

    }

    public function change_status_category(Request $request)
    {
        $category = Category::find($request->cat_id);
        $category->status = $request->status;

        $category->save();
        return response()->json(['success'=>'Success']);
    }

    public function change_status_show(Request $request)
    {
        $category = Category::find($request->cat_id);
        $category->show_status = $request->status;

        $category->save();
        return response()->json(['success'=>'Success']);
    }

    public function search_category(Request $request)
    {
        $data = new Category();
        $data = $data->where('status',1);
        if($request->has('q')){
            $search = $request->q;
            $data = $data->where('name','like','%'.$search.'%');
        }
       
        $data = $data->get();
        return response()->json($data);
    }

   
}
