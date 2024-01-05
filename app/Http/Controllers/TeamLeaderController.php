<?php

namespace App\Http\Controllers;

use App\TeamLeader;
use App\Group;
use App\Technician;
use Illuminate\Http\Request;
use DB;
use Validator;
use App\TeamMember;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\CustomClasses\ColectionPaginate;
use URL;
class TeamLeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $team_leaders = new TeamLeader();
        $team_members = TeamMember::all();
        $teams = Group::all()->sortBy('group_name');
        
        $team_leaders = $team_leaders->leftjoin('technicians','technicians.id','=','team_leaders.tech_id')
            ->leftjoin('groups','groups.id','=','team_leaders.team_id')
            ->select(
                'team_leaders.*',
                'groups.group_name AS team',
                'technicians.name AS tech_name',
                'technicians.photo',
                'technicians.path',
              
            );

        if ($request->team_id != '') {
            $team_leaders = $team_leaders->where('team_leaders.team_id',$request->team_id);
        }
        
            // dd($team_leaders);
        $team_leaders = $team_leaders->orderBy('groups.group_name')->get();
        foreach ($team_leaders as $key => $leaders) {
            $leaders->members = $this->get_leader($leaders->team_id);
        }
        // dd($team_leaders);

        $team_leaders = $this->paginate($team_leaders,$request->team_id); 
        return view('admin.team_leader.index',compact('team_leaders','team_members','teams'));
    }

    public function get_leader($leader_id)
    {
        $team_members = new TeamMember();
        $team_members = $team_members->leftJoin('technicians','technicians.id','=','team_members.member_id')->select('technicians.name','technicians.photo','technicians.path')->where('leader_id',$leader_id)->get();
        return $team_members;
    }

    public function paginate($items, $team_id, $perPage = 10, $page = null, $options = [])
    {
        $url = URL::to('/') . '/team_leaders?team_id='.$team_id;

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            array('path' => $url)
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teams = Group::all();
        $technicians = Technician::all();
        return view('admin.team_leader.create',compact('teams','technicians'));
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
           'member_id'=> 'required',
            'team_id'=>'required',
            'leader_id'=>'required'
        ];
         $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()){
              DB::beginTransaction();
              try {
                   $team_leaders = TeamLeader::create([
                        'team_id'=>$request->team_id,
                        'team_status'=>'leader',
                        'tech_id'=>$request->leader_id,
                   ]);

                   foreach ($request->member_id as $key => $member) {
                       $team_members = TeamMember::create([
                        'leader_id'=>$team_leaders->team_id,
                        'member_id'=>$member
                    ]);
                   }
                   
                  DB::commit();
              } catch (Exception $e) {
                  DB::rollback();
                     return redirect()->route('team_leaders.index')->with('error','Something wrong!');
              }
               return redirect()->route('team_leaders.index')->with('success','Success');
          }else{
              return redirect()->route('team_leaders.index')->with('error','Something wrong!');
          }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TeamLeader  $teamLeader
     * @return \Illuminate\Http\Response
     */
    public function show(TeamLeader $teamLeader)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TeamLeader  $teamLeader
     * @return \Illuminate\Http\Response
     */
    public function edit(TeamLeader $teamLeader)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TeamLeader  $teamLeader
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TeamLeader $teamLeader)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TeamLeader  $teamLeader
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
        $teamLeader = TeamLeader::find($id);
        $delete_team_id = $teamLeader->delete();
        
        $team_members = TeamMember::where('leader_id',$teamLeader->team_id)->delete();
        return redirect()->route('team_leaders.index')->with('success','Success');
    }

    public function select_team(Request $request)
    {
        // dd($request->all());
        if($request->ajax()){
            $technicians = Technician::where('group_id',$request->id)->get();
            // dd($technicians);
            echo "<option value=''>Select Team Leader</opiton>";
            foreach ($technicians as $key => $technician) {
                echo "<option value='".$technician->id."'>".$technician->name."</opiton>";
            }
        }
    }

    public function get_technicians(Request $request)
    {
        $data = Technician::where('group_id',$request->team_id)->where('id','!=',$request->leader_id);

        if($request->has('q')){
            $search = $request->q;
            $data = $data->where('name','like','%'.$search.'%');
        }
       
        $data = $data->get();
        
        return response()->json($data);
    }
}
