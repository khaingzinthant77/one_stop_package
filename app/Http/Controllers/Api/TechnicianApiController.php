<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Survey;
use App\Customer;
use App\TeamMember;
use App\TeamLeader;
use Carbon\Carbon;
use DB;
use File;
use Image;
use Validator;

class TechnicianApiController extends Controller
{
	public function get_team_member(Request $request)
	{
		$team_members = new TeamMember();
        $team_members = $team_members->leftJoin('technicians','technicians.id','=','team_members.member_id')->select('technicians.name','technicians.photo','technicians.path','technicians.phone_no','team_members.leader_id')->where('leader_id',$request->team_id)->get();
        // $members = [];
        if ($team_members->count() > 0) {
            $team_leaders = new TeamLeader();
            $team_leaders =  $team_leaders->leftJoin('technicians','technicians.id','=','team_leaders.tech_id')->select('technicians.name','technicians.photo','technicians.path','technicians.phone_no','team_status')->where('team_id',$team_members[0]->leader_id)->first();
            // dd($team_members->toArray());
            $memberArr = $team_members->toArray();
            array_push($memberArr,$team_leaders);
        }else{
            $memberArr = [];
        }
        

        
        // array_push($members,$team_leaders);
        // foreach ($team_members as $key => $member) {
            

        //     array_push($members,$team_leaders);
        // }
         return response([
                    'message'=>"Success",
                    'status'=>1,
                    // 'team_leader'=>$team_leaders,
                    'teams'=>$memberArr,
                ]);
	}
}