<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;
use Validator;
use App\User;
use App\Technician;
use App\Survey;
use DB;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd("Jere");
        $groups = new Group();
        if ($request->name != '') {
            $groups = $groups->where('group_name','like','%'.$request->name.'%')->orwhere('loginId','like','%'.$request->name.'%');
        }
        $groups = $groups->orderBy('group_name')->paginate(10);
        $count = $groups->count();
        return view('admin.group.index',compact('groups','count'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.group.create');
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
            'loginId'=>'required'
        ];

       $validator = Validator::make($request->all(), $rules);
       if ($validator->passes()) {
          DB::beginTransaction();
         try {
                $group = Group::create([
                    'group_name' => $request->name,
                    'loginId' => $request->loginId,
                ]);
                
                $user = User::create([
                    'name' => $request->name,
                    'login_id' => $request->loginId,
                    'group_id'=>$group->id
                ]);
                 DB::commit();
             
            } catch (Exception $e) {
                  DB::rollback();
                return redirect()->route('group.index')
                            ->with('error','Something wrong!');
            }
                return redirect()->route('group.index')
                ->with('success', 'Success');
           }else{
             return redirect()->route('group.index')->with('error','Something wrong!');
           }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = Group::find($id);
        return view('admin.group.edit',compact('group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $rules = [
            'name'=>'required',
            'loginId'=>'required'
        ];

        $validate = $this->validate($request, $rules);

        $group = Group::find($id);

        $arr = [
            'group_name' => $request->name,
            'loginId' => $request->loginId,
        ];


        $group = $group->update($arr);
        $user = User::where('group_id',$id)->update(['name'=>$request->name,'login_id'=>$request->loginId]);
        $values = Survey::where('survey_by', $id)->update(['survey_name'=>$request->name]);

        return redirect()->route('group.index')
            ->with('success', 'Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $technicians = Technician::where('group_id',$id)->get();
        if ($technicians->count()>0) {
            return redirect()->route('group.index')->with('error','Team cannot delete!!');
        }else{
            $group = Group::findorfail($id);

            $group = $group->delete();

            return redirect()->route('group.index')->with('success','Success');
        }

        
    }
}
