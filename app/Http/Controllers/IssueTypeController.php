<?php

namespace App\Http\Controllers;

use App\IssueType;
use App\Ticket;
use Illuminate\Http\Request;

class IssueTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $issue_types = new IssueType();

        if ($request->name != '') {
            $issue_types = $issue_types->where('issue_type','like','%'.$request->name.'%');
        }
        
        $count=$issue_types->get()->count();
        $issue_types = $issue_types->orderBy('issue_type','ASC')->paginate(10);

        return view('admin.issue_type.index',compact('issue_types','count'))->with('i', (request()->input('page', 1) - 1) * 10);
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
        $rules = [
            'issue_type'=>'required',
        ];

        $this->validate($request,$rules);

        $issue_type = IssueType::create([
                    'issue_type'=> $request->issue_type,
                ]);
  
          return redirect()->route('issue_type.index')
                        ->with('success','Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\IssueType  $issueType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $issueType = IssueType::find($id);
        return view('admin.issue_type.show',compact('issueType'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\IssueType  $issueType
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $issueType = IssueType::find($id);
        return view('admin.issue_type.edit',compact('issueType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\IssueType  $issueType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $rules = [
            'issue_type'=>'required',
        ];

        $this->validate($request,$rules);

        $issue_type = IssueType::find($id)->update([
                    'issue_type'=> $request->issue_type,
                ]);
  
          return redirect()->route('issue_type.index')
                        ->with('success','Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\IssueType  $issueType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ticket_count = Ticket::where('issue_id',$id)->get()->count();
        if ($ticket_count == 0) {
            //delete
            $issue_type = IssueType::find($id)->delete();
            return redirect()->route('issue_type.index')
                        ->with('success','Successfully.');
        }else{
            return redirect()->route('issue_type.index')
                        ->with('error','Issue Type cannot delete!.');
        }
    }

    public function change_status_issue(Request $request)
    {
        $issue_type = IssueType::find($request->type_id);
        $issue_type->status = $request->status;

        $issue_type->save();
        return response()->json(['success'=>'Success']);
    }
}
