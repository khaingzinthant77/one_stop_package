<?php

namespace App\Http\Controllers;

use App\Technician;
use App\User;
use App\TeamMember;
use App\TeamLeader;
use Illuminate\Http\Request;
use Validator;
use DB;
use File;
use App\Group;

class TechnicianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd("Here");
        $technicians = new Technician();
        if ($request->name != '') {
            $technicians = $technicians->where('name','like','%'.$request->name.'%')->orwhere('phone_no','like','%'.$request->name.'%');
        }

        if ($request->team_id != '') {
            $technicians = $technicians->where('group_id',$request->team_id);
        }
        $count = $technicians->count();
        $technicians = $technicians->orderBy('created_at','asc')->paginate(10);

        $teams = Group::all()->sortBy('group_name');

        return view('admin.technician.index',compact('technicians','count','teams'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::all();
        return view('admin.technician.create',compact('groups'));
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
            'name' => 'required',
            'phone_no' => 'required',
            'group_id'=>'required'
        ];

       $validator = Validator::make($request->all(), $rules);
       if ($validator->passes()) {
          DB::beginTransaction();
         try {
                $destinationPath = public_path() . '/uploads/technician/';
                $photo = "";
                //upload image
                if ($file = $request->file('photo')) {
                    $extension = $file->getClientOriginalExtension();
                    $safeName = str_random('10') . '.' . $extension;
                    $file->move($destinationPath, $safeName);
                    $photo = $safeName;
                }

                $arr = [
                        'name' => $request->name,
                        'phone_no' => $request->phone_no,
                        'group_id' => $request->group_id,
                        'path'=>'uploads/technician/',
                        'photo' => $photo,
                    ];

                $technician = Technician::create($arr);
                 DB::commit();
             
             } catch (Exception $e) {
                  DB::rollback();
                    return redirect()->route('technician.index')
                                ->with('error','Something wrong!');
             }
              return redirect()->route('technician.index')
                ->with('success', 'Success');
           }else{
            // dd("ERROR");
             return redirect()->route('technician.index')->with('error','Something wrong!');
           }
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Technician  $technician
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $technician= Technician::findorfail($id);
        return view('admin.technician.show',compact('technician'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Technician  $technician
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $technician = Technician::find($id);
        $groups = Group::all();
        return view('admin.technician.edit',compact('technician','groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Technician  $technician
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $rules = [
            'name' => 'required',
            'phone_no' => 'required',
        ];

        $validate = $this->validate($request, $rules);

        $technician = Technician::find($id);
       
        $destinationPath = public_path() . '/uploads/technician/';

        $photo = ($request->photo != '') ? $request->photo : $technician->photo;
        //upload image
        if ($file = $request->file('photo')) {
            $extension = $file->getClientOriginalExtension();
            $safeName = str_random('10') . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $photo = $safeName;
        }
        
        $arr = [
            'name' => $request->name,
            'phone_no' => $request->phone_no,
            'photo' => $photo,
            'path'=>'uploads/technician/'
        ];

        $technician = $technician->update($arr);

        return redirect()->route('technician.index')
            ->with('success', 'Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Technician  $technician
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $team_leaders = TeamLeader::where('tech_id',$id)->get()->count();
        $team_members = TeamMember::where('member_id',$id)->orWhere('leader_id',$id)->get()->count();
        if ($team_leaders != 0 || $team_members != 0) {
            return redirect()->route('technician.index')
                    ->with('error', 'Technician can not delete!');
        }else{
            
                $storagePath = public_path() . '/uploads/technician/';

                $technician = Technician::findorfail($id);
                
                if (File::exists($storagePath . $technician->photo)) {
                    File::delete($storagePath . $technician->photo);
                }

                $technician->delete();
                return redirect()->route('technician.index')
                    ->with('success', 'Success');
            
        }
        
        
        
    }

    public function change_status_tech(Request $request)
    {
        $technician = Technician::find($request->tech_id);
        $technician->status = $request->status;

        $technician->save();
        return response()->json(['success'=>'Success']);
    }

} 
