<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use App\Imports\ItemsImport;
use App\Exports\ItemsExport;
use App\SurveyInstallItem;
use Maatwebsite\Excel\Facades\Excel;
use App\Category;
use App\Group;
use App\Brand;
use App\Assign;
use DB;
use File;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
         $category = Category::all();
         $brand = Brand::all();
         
        $item = new Item();
        if ($request->item_name != '') {
            $item = $item->Where('model','like','%'.$request->item_name.'%');
        }
         if ($request->cat_id != '') {
            $item = $item->where('cat_id',$request->cat_id);
        }
         if ($request->brand_id != '') {
            $item = $item->where('brand_id',$request->brand_id);
        }
        $count = $item->get()->count();
    
        $item = $item->orderBy('created_at','desc')->paginate(10);

        return view('admin.item.index',compact('item','category','count','brand'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::all();
        $brand = Brand::all();
        $items = Item::all();

        $modelArr = [];
        foreach ($items as $key => $item) {
            $model = $item->model;
            array_push($modelArr, $model);
        }

        $modelArr = json_encode($modelArr);

        return view('admin.item.create',compact('category','brand','modelArr'));
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
            'model'=>'required',
            'unit'=>'required',
            'cat_id'=>'required',
            'brand_id'=>'required',
            'qty'=>'required'
        ];

    $this->validate($request,$rules);
    $destinationPath = public_path() . '/uploads/productPhoto/';
      $photo = "";
        //upload image
        if ($file = $request->file('photo')) {
            $extension = $file->getClientOriginalExtension();
            $safeName = str_random('10') . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $photo = $safeName;
        }

    $item = Item::create([
        'cat_id'=>$request->cat_id,
        'brand_id'=>$request->brand_id,
        'model'=>$request->model,
        'item_code'=>$request->item_code,
        'qty' =>$request->qty,  
        'unit'=>$request->unit == 0 ? 'unit' : 'meter',
        'price'=>$request->price,
        'remark'=>$request->remark,
        'path'=>'uploads/productPhoto/',
        'photo'=>$photo,
    ]);
    
       return redirect()->route('item.index')->with('success','Item created successfully');;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand=Brand::all();
        $category=Category::all();
        $item=Item::find($id);
        return view('admin.item.show',compact('item','brand','category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        // dd($id);
        $categories = Category::all();
        $brand =Brand::all();
        $r_item=Item::find($id); 
        
        $items = Item::all();
        $modelArr = [];
        foreach ($items as $key => $item) {
            $model = $item->model;
            array_push($modelArr, $model);
        }
        // dd($modelArr);
        $modelArr = json_encode($modelArr);

        return view('admin.item.edit',compact('r_item','categories','brand','modelArr'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       // dd($request->all());
        $items = Item::find($id);
        
        $destinationPath = public_path() . '/uploads/productPhoto/';
        $photo = ($request->photo != '') ? $request->photo : $items->photo;
        //upload image
        if ($file = $request->file('photo')) {
            $extension = $file->getClientOriginalExtension();
            $safeName = str_random('10') . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $photo = $safeName;
        }

       
        $items = $items->update([
            'cat_id'=>$request->cat_id,
            'brand_id'=>$request->brand_id,
            'model'=>$request->model,
            'item_code'=>$request->item_code,
           
            'is_serialno'=>0,
            'qty'=>$request->qty,
            'unit'=>$request->unit == 0 ? "unit" : "meter",
            'price'=>$request->price,
            'photo'=>$photo,
            'remark'=>$request->remark,

        ]);
        
       return redirect()->route('item.index')->with('success','Item updated successfully');
   
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $survey_install_item = SurveyInstallItem::where('item_id',$id)->get()->count();
        if ($survey_install_item == 0) {
            $item = Item::find($id)->delete();
            return redirect()->route('item.index')->with('success','Success');
        }else{
            return redirect()->route('item.index')->with('error','Item can not delete!');
        }
    }

    public function get_model(Request $request)
    {
        if ($request->keyword != null) {
            // dd("Here");
            $items = new Item();

            $get_models = $items->where('model','like','%'.$request->keyword.'%');
            // dd($get_models);
            echo "<ul>";
            foreach ($get_models as $key => $sec) {
                echo "<option value='".$sec->id."'>".$sec->model."</opiton>";
            }
        }
    }

    public function import(Request $request) 
    {
        $request->validate([
            'file'=>'required',
        ]);

        Excel::import(new ItemsImport,request()->file('file'));
             
        return back();
    }

    public function export() 
    {
        return Excel::download(new ItemsExport, 'item.xlsx');
    }

    public function camera_report(Request $request)
    {
        // dd($request->all());
        $groups = Group::orderBy('group_name','asc')->get();
        
        foreach ($groups as $key => $value) {
            $value->items = $this->get_items($value->id,$request);
        }

        // dd($groups);
        return view('admin.item.camera_report',compact('groups'));

    }

    public function get_items($team_id,$request)
    {
        $category_list = Category::where('show_status',1)->get();
        // return $category_list;
        foreach ($category_list as $key => $value) {
            $value->total_count += $this->get_qty($value->id,$team_id,$request);
        }

        return $category_list;
    }

    public function get_qty($cat_id,$team_id,$request)
    {
        // dd($cat_id);
        $survey_ids = [];
        $ticket_ids = [];
        $assigns = new Assign();
        $assigns = $assigns->where('team_id',$team_id)->select('survey_id','ticket_id','solved_date')->where('is_solve',1);
      // dd($assigns->get());

        if ($request->from_date != null && $request->to_date != null) {
            $from_date = date('Y-m-d',strtotime($request->from_date)).' 00:00:59';
            $to_date = date('Y-m-d',strtotime($request->to_date)).' 23:59:59';
            $assigns = $assigns->whereBetween('solved_date',[$from_date,$to_date]);
        }

        // dd($assigns->get());
        foreach ($assigns->get() as $key => $value) {
            array_push($survey_ids,$value->survey_id);
            array_push($ticket_ids, $value->ticket_id);
        }

        // $install_item = SurveyInstallItem::where('cat_id',$cat_id)->first();
        $survey_install_items = SurveyInstallItem::whereIn('survey_id',$survey_ids)->where('cat_id',$cat_id)->get();
        $ticket_instsall_items = SurveyInstallItem::whereIn('ticket_id',$ticket_ids)->where('cat_id',$cat_id)->get();

        $survey_qty = 0;
        $ticket_qty = 0;
        foreach ($survey_install_items as $key => $value) {
            $survey_qty += $value->qty;
        }

        foreach ($ticket_instsall_items as $key => $value) {
            $ticket_qty += $value->qty;
        }

        $total_qty = $survey_qty + $ticket_qty;
        return $total_qty;;
    }
  
}
