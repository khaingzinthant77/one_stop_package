<?php

namespace App\Http\Controllers;

use App\Survey;
use App\Township;
use App\Group;
use App\Item;
use App\Category;
use App\ServiceCharge;
use App\Assign;
use App\Photo;
use App\SurveyInstallItem;
use App\Exports\SurveyExport;
use App\Amount;
use App\Customer;
use App\TicketAssign;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Validator;
use Carbon\Carbon;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $assigns = new Assign();
        $assigns = $assigns->leftJoin('surveys', function ($join) {
            $join->on('assigns.survey_id', '=', 'surveys.id');
        })
            ->leftjoin('groups', 'groups.id', '=', 'assigns.team_id')
            ->leftjoin('customers', 'customers.id', '=', 'surveys.cust_id')
            ->leftjoin('townships', 'townships.id', '=', 'customers.tsh_id')
            ->select([
                'surveys.*',
                'customers.name',
                'customers.phone_no',
                'townships.town_name',
                'customers.address',
                'groups.group_name AS group_name',
                'assigns.assign_date',
                'assigns.is_solve',
                'assigns.team_id',
                'assigns.admin_check',
                'assigns.checked_by'
            ])->where('survey_id', '!=', null)->where('surveys.is_solve', 0)->where('archive_status', 1);

        if ($request->keyword != null) {
            $assigns = $assigns->where('customers.name','like','%'.$request->keyword.'%');
        }
        if ($request->tsh_id != null) {
            $assigns = $assigns->where('tsh_id', $request->tsh_id);
        }

        if ($request->team_id != null) {
            $assigns = $assigns->where('team_id', $request->team_id);
        }

        if ($request->is_install != null) {
            $assigns = $assigns->where('is_install',$request->is_install);
        }

        if ($request->assign_status != null) {
            // dd($request->assign_status);
            $assigns = $assigns->where('assign_status', $request->assign_status);
        }

        // dd($assigns->get());

        if ($request->solve_status != null) {
            $assigns = $assigns->where('surveys.is_solve', $request->solve_status);
        }



        if ($request->from_date != null && $request->to_date != null) {
            $from_date = date('Y-m-d', strtotime($request->from_date)) . " 00:00:00";
            $to_date = date('Y-m-d', strtotime($request->to_date)) . " 23:59:59";

            // dd($from_date,$to_date);
            $assigns = $assigns->whereBetween('surveys.created_at', [$from_date, $to_date]);
        }



        $count = $assigns->get()->count();
        $assigns = $assigns->orderBy('surveys.created_at', 'DESC')->paginate(10);

        $townships = Township::all();
        $teams = Group::orderBy('group_name')->get();

        return view('admin.survey.index', compact('assigns', 'count', 'townships', 'teams'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $townships = Township::all();
        $teams = new Group();
        $teams = $teams->orderBy('group_name')->get();
        $items = Item::where('status', 1)->get();
        return view('admin.survey.create', compact('townships', 'teams', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();
        // dd($input);
        $rules = [
            'name' => 'required',
            'ph_no' => 'required',
            'tsh_id' => 'required',
            'address' => 'required',
            'sub_total' => 'required',
            'install_charge' => 'required',
            'total_amt' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        $hour = date('H:i:s');
        if ($validator->passes()) {

            DB::beginTransaction();
            try {

                // $surveys = Survey::all();
                // $count = $surveys->count();
                // $cust_count = str_pad(++$count,4,"0",STR_PAD_LEFT);
                // $voucher_no = date('Ym')."-".$cust_count;

                $customer = Customer::create([
                    'name' => $request->name,
                    'phone_no' => $request->ph_no,
                    'tsh_id' => $request->tsh_id,
                    'address' => $request->address,
                ]);
                $survey = Survey::create([
                    'cust_id' => $customer->id,
                    'lat' => $request->lat,
                    'lng' => $request->lng,
                    'assign_status' => $request->team_id != null ? 1 : 0,
                    'survey_by' => auth()->user()->id,
                    'survey_name' => auth()->user()->name,
                    'remark' => $request->remark,
                    'survey_type' => 1
                ]);

                // if ($request->team_id != null) {
                $assign = Assign::create([
                    'survey_id' => $survey->id,
                    'team_id'  => $request->team_id != null ? $request->team_id : null,
                    'assign_date' => $request->team_id != null ? date('Y-m-d', strtotime($request->assign_date)) . ' ' . $hour : null,
                    'appoint_date' => $request->team_id != null ? date('Y-m-d', strtotime($request->appoint_date)) : null
                ]);
                // }

                // Photo
                $date = Carbon::now();
                $timeInMilliseconds = $date->getPreciseTimestamp(3);

                $destinationPath = public_path() . '/uploads/survey/';
                $photo = "";
                foreach ($request->images as $key => $img) {
                    if ($file = $img) {
                        $extension = $file->getClientOriginalExtension();
                        $safeName = 'img' . $timeInMilliseconds . $key . '.' . $extension;
                        $file->move($destinationPath, $safeName);
                        $photo = $safeName;
                    }

                    $photos = Photo::create([
                        'survey_id' => $survey->id,
                        'path' => 'uploads/survey/',
                        'photo_name' => $photo
                    ]);
                }
                foreach ($request->actual_item as $key => $value) {
                    $survey_install_item = SurveyInstallItem::create([
                        'survey_id' => $survey->id,
                        'item_id' => $request->install_amt[$key],
                        'cat_id' => $request->cat_id[$key],
                        'cat_price' => $request->cat_price[$key],
                        'qty' => $request->actual_qty[$key],
                        'item_price' => $request->price[$key],
                        'amount' => $request->amount[$key],
                    ]);
                }

                $total_amt = Amount::create([
                    'survey_id' => $survey->id,
                    'sub_total' => $request->sub_total,
                    'total_amt' => $request->total_amt,
                    'install_charge' => $request->install_charge,
                    'is_cloud' => $request->cloud_charge == null ? 0 : 1,
                    'cloud_charge' => $request->cloud_charge == null ? 0 : $request->cloud_charge,
                    'cabling_charge' => $request->cabling_charge == null ? 0 : $request->cabling_charge,
                    'discount' => $request->discount == null ? 0 : $request->discount
                ]);

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->route('surveys.index')->with('error', 'Something wrong!');
            }
        } else {
            return redirect()->route('surveys.index')->with('error', 'Something wrong!');
        }
        return redirect()->route('surveys.index')->with('success', 'Success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $survey = new Survey();
        $survey = $survey->leftjoin('customers', 'customers.id', '=', 'surveys.cust_id')
            ->leftJoin('townships', 'townships.id', '=', 'customers.tsh_id')
            ->select([
                'surveys.*',
                'customers.name',
                'customers.phone_no',
                'customers.address',
                'townships.town_name'
            ])->find($id);
        $photos = Photo::where('survey_id', $id)->get();
        $assign = new Assign();
        $assign = $assign->leftJoin('groups', 'groups.id', '=', 'assigns.team_id')
            ->select([
                'groups.group_name',
                'assigns.assign_date',
                'assigns.appoint_date'
            ])->where('survey_id', $id)->first();
        // dd($assign);
        $survey_install_items = new SurveyInstallItem();

        $survey_install_items = $survey_install_items->leftJoin('items', 'items.id', 'survey_install_items.item_id')->select([
            'survey_install_items.*',
            'items.model',
            'items.model',
            'survey_install_items.qty',
            'survey_install_items.amount',
            'items.unit',
            'survey_install_items.item_price'
        ])->where('survey_id', $id)->get();

        $amounts = Amount::where('survey_id', $id)->first();
        return view('admin.survey.show', compact('survey', 'photos', 'assign', 'survey_install_items', 'amounts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $townships = Township::all();
        $teams = new Group();
        $teams = $teams->orderBy('group_name')->get();
        $items = Item::where('status', 1)->get();

        $survey = new Survey();
        $survey = $survey->leftjoin('customers', 'customers.id', '=', 'surveys.cust_id')
            ->leftJoin('townships', 'townships.id', '=', 'customers.tsh_id')
            ->select([
                'surveys.*',
                'customers.name',
                'customers.phone_no',
                'customers.address',
                'customers.tsh_id',
                'townships.town_name'
            ])->find($id);
        $photos = Photo::where('survey_id', $id)->get();
        // dd($photos);
        $assign = new Assign();
        $assign = $assign->leftJoin('groups', 'groups.id', '=', 'assigns.team_id')
            ->select([
                'groups.id',
                'groups.group_name',
                'assigns.assign_date',
                'assigns.appoint_date'
            ])->where('survey_id', $id)->first();
        // dd($assign);
        $ticket_assign = null;
        if ($assign == null) {
            $ticket_assign = new TicketAssign();
            $ticket_assign = $ticket_assign->leftJoin('groups', 'groups.id', '=', 'ticket_assigns.team_id')
                ->select([
                    'groups.id',
                    'groups.group_name',
                    'ticket_assigns.assign_date',
                    'ticket_assigns.appoint_date'
                ])->where('ticket_id', $id)->first();
        }

        // dd($ticket_assign);

        $survey_install_items = new SurveyInstallItem();

        $survey_install_items = $survey_install_items
            ->leftJoin('items', 'items.id', 'survey_install_items.item_id')
            ->leftjoin('categories', 'categories.id', '=', 'survey_install_items.cat_id')
            ->select([
                'survey_install_items.*',
                'items.id AS item_id',
                'items.model',
                'items.model',
                'survey_install_items.qty',
                'survey_install_items.amount',
                'items.unit',
                'survey_install_items.item_price',
                'categories.install_charge AS price'
            ])->where('survey_id', $id)->get();

        // dd($survey_install_items);

        $amounts = Amount::where('survey_id', $id)->first();

        return view('admin.survey.edit', compact('survey', 'photos', 'assign', 'survey_install_items', 'amounts', 'townships', 'teams', 'items', 'ticket_assign'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        // dd($input);
        $rules = [
            'name' => 'required',
            'ph_no' => 'required',
            'tsh_id' => 'required',
            'address' => 'required',

        ];
        $validator = Validator::make($input, $rules);
        $hour = date('H:i:s');
        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $survey = Survey::find($id)->update([
                    'lat' => $request->lat,
                    'lng' => $request->lng,
                    'assign_status' => $request->team_id != null ? 1 : 0,
                    'remark' => $request->remark
                ]);

                $cust_id = Survey::find($id)->cust_id;
                $customer = Customer::find($cust_id)->update([
                    'name' => $request->name,
                    'phone_no' => $request->ph_no,
                    'tsh_id' => $request->tsh_id,
                    'address' => $request->address,

                ]);

                $assign = Assign::where('survey_id', $id)->first();

                if ($request->team_id != null && $assign == null) {
                    $assign = Assign::create([
                        'survey_id' => $id,
                        'team_id'  => $request->team_id,
                        'assign_date' => date('Y-m-d', strtotime($request->assign_date)) . ' ' . $hour,
                        'appoint_date' => date('Y-m-d', strtotime($request->appoint_date))
                    ]);
                } else {
                    $assign = $assign->update([
                        'team_id'  => $request->team_id,
                        'assign_date' => date('Y-m-d', strtotime($request->assign_date)) . ' ' . $hour,
                        'appoint_date' => date('Y-m-d', strtotime($request->appoint_date))
                    ]);
                }


                // Photo
                $date = Carbon::now();
                $timeInMilliseconds = $date->getPreciseTimestamp(3);

                $destinationPath = public_path() . '/uploads/survey/';
                $photo = "";

                if ($request->images != null) {
                    foreach ($request->images as $key => $img) {
                        if ($file = $img) {
                            $extension = $file->getClientOriginalExtension();
                            $safeName = 'img' . $timeInMilliseconds . $key . '.' . $extension;
                            $file->move($destinationPath, $safeName);
                            $photo = $safeName;
                        }

                        $photos = Photo::create([
                            'survey_id' => $id,
                            'path' => 'uploads/survey/',
                            'photo_name' => $photo
                        ]);
                    }
                }

                $survey_install_count = SurveyInstallItem::where('survey_id', $id)->get()->count();
                // dd($survey_install_count);
                if ($survey_install_count != 0) {
                    if ($request->total_amt != null) {
                        $survey_install_items = SurveyInstallItem::where('survey_id', $id)->delete();
                        foreach ($request->actual_item as $key => $value) {
                            $survey_install_item = SurveyInstallItem::create([
                                'survey_id' => $id,
                                'item_id' => $request->install_amt[$key],
                                'cat_id' => $request->cat_id[$key],
                                'cat_price' => $request->cat_price[$key],
                                'qty' => $request->actual_qty[$key],
                                'item_price' => $request->price[$key],
                                'amount' => $request->amount[$key],
                            ]);
                        }

                        $total_amt = Amount::where('survey_id', $id)->first();
                        if ($total_amt != null) {
                            $total_amt = $total_amt->update([
                                'sub_total' => $request->sub_total,
                                'total_amt' => $request->total_amt,
                                'install_charge' => $request->install_charge,
                                'is_cloud' => $request->cloud_charge == null ? 0 : 1,
                                'cloud_charge' => $request->cloud_charge == null ? 0 : $request->cloud_charge,
                                'cabling_charge' => $request->cabling_charge == null ? 0 : $request->cabling_charge,
                                'discount' => $request->discount == null ? 0 : $request->discount
                            ]);
                        } else {
                            // dd($request->all());
                            $total_amt = Amount::create([
                                'survey_id' => $id,
                                'sub_total' => $request->sub_total,
                                'total_amt' => $request->total_amt,
                                'install_charge' => $request->install_charge,
                                'is_cloud' => $request->cloud_charge == null ? 0 : 1,
                                'cloud_charge' => $request->cloud_charge == null ? 0 : $request->cloud_charge,
                                'cabling_charge' => $request->cabling_charge == null ? 0 : $request->cabling_charge,
                                'discount' => $request->discount == null ? 0 : $request->discount
                            ]);
                        }
                    }
                }



                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->route('surveys.index')->with('error', 'Something wrong!');
            }
        } else {
            return redirect()->route('surveys.index')->with('error', 'Something wrong!');
        }
        return redirect()->route('surveys.index')->with('success', 'Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
        $survey = Survey::find($id)->update([
            'archive_status' => 0
        ]);
        return redirect()->route('surveys.index')->with('success', 'Success');
    }

    public function get_install_amt(Request $request)
    {
        // dd($request->all());
        $item = Item::find($request->item_id);
        $amt = Category::find($item->cat_id)->install_charge;
        return response()->json($amt);
    }

    public function get_cloud_charge()
    {
        $service_charge = ServiceCharge::all();
        // dd($service_charge);
        if (count($service_charge) > 0) {
            return response()->json($service_charge[0]->price);
        } else {
            return response()->json(0);
        }
    }

    public function get_cat_price(Request $request)
    {
        // dd($request->cat_id);
        $price = Category::find($request->cat_id)->install_charge;
        // dd($price);
        return response()->json($price);
    }

    public function delete_img(Request $request)
    {
        // dd($request->all());
        $photo = Photo::find($request->img_id)->delete();
        return response()->json(1);
    }

    public function update_survey_check(Request $request)
    {
        // dd($request->all());
        $assigns = Assign::where('survey_id', $request->survey_id)->update([
            'admin_check' => 1,
            'checked_by' => auth()->user()->name
        ]);

        // dd($assigns);
        return response()->json(1);
    }


    public function survey_export(Request $request)
    {
        return Excel::download(new SurveyExport, 'survey.xlsx');
    }

    public function not_install_update(Request $request)
    {
        // dd($request->all());
        $survey = Survey::find($request->survey_id);
        $survey = $survey->update([
            'remark'=>$request->remark,
            'is_install'=>0
        ]);

        return redirect()->route('surveys.index')->with('success','Success');
    }
}