<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Survey;
use App\Customer;
use App\Township;
use App\InstallItem;
use App\SurveyInstallItem;
use App\Assign;
use App\Photo;
use App\Amount;
use App\Signature;
use App\Group;
use App\User;
use Carbon\Carbon;
use DB;
use File;
use Image;
use Validator;

class SurveyApiController extends Controller
{
    /**
    * Survey.
    *
    * @return Response
    */
    
    public function survey_history(Request $request)
    {
        $input = $request->all();
           $rules=[
                'team_id'=>'required',
                'page'=>'required',
          ];
          $validator = Validator::make($input, $rules);
          $response = array('response' => '', 'success'=>false);
           if ($validator->fails()) {
              $messages = $validator->messages();
                  // $response['response'] = $validator->messages()->first();
                  // return $response;
                return response([
                    'message'=>$validator->messages()->first(),
                    'status'=>0,
                ]);
          }else{
            //from,to

            // $assigns = new Assign();
            // $assigns = $assigns->leftJoin('surveys',function($join){
            //                     $join->on('assigns.survey_id', '=', 'surveys.id');
            //                 })
            //                 ->leftjoin('groups','groups.id','=','assigns.team_id')
            //                 ->leftjoin('customers','customers.id','=','surveys.cust_id')
            //                 ->leftjoin('townships','townships.id','=','customers.tsh_id')
            //                 ->select([
            //                     'surveys.id',
            //                     'surveys.lat',
            //                     'surveys.lng',
            //                     'customers.name',
            //                     'customers.phone_no',
            //                     'townships.town_name',
            //                     'customers.address',
            //                     'surveys.is_solve',
            //                     'surveys.created_at'
            //                 ])->where('survey_id','!=',null)->where('surveys.survey_by',$request->team_id);
            // Survey::where('survey_by',$request->team_id)->get()->count();
            $assigns = new Survey();
            $assigns = $assigns->leftJoin('customers','customers.id','=','surveys.cust_id')
                                ->leftjoin('townships','townships.id','=','customers.tsh_id')
                                ->select([
                                    'surveys.id',
                                    'surveys.lat',
                                    'surveys.lng',
                                    'customers.name',
                                    'customers.phone_no',
                                    'townships.town_name',
                                    'customers.address',
                                    'surveys.is_solve',
                                    'surveys.created_at',
                                    'surveys.admin_check',
                                    'surveys.checked_by'
                                ])->where('survey_by',$request->team_id);

            if ($request->keyword != null) {
                $assigns = $assigns->where('customers.name','like','%'.$request->keyword.'%')->orwhere('customers.phone_no','like','%'.$request->keyword.'%');
            }
            if ($request->from_date != null && $request->to_date != null) {
                $from_date = date('Y-m-d',strtotime($request->from_date))." 00:00:00";
                $to_date = date('Y-m-d',strtotime($request->to_date))." 23:59:59";

                // dd($from_date,$to_date);
                $assigns = $assigns->whereBetween('surveys.created_at',[$from_date,$to_date]);
            }
            $assigns = $assigns->orderBy('surveys.created_at','DESC')->paginate(10);

            return response([
                    'message'=>"Success",
                    'status'=>1,
                    'survey_history'=>$assigns
                ]);
          }
    }

    public function solve_survey(Request $request)
    {
        $input = $request->all();
           $rules=[
                'is_solve'=>'required',
                'survey_id'=>'required',
                'solved_by'=>'required',
                'photos'=>'required',
                'is_survey'=>'required'
          ];
          $validator = Validator::make($input, $rules);
          $response = array('response' => '', 'success'=>false);
           if ($validator->fails()) {
              $messages = $validator->messages();
                  return response([
                    'message'=>$validator->messages()->first(),
                    'status'=>0,
                ]);
          }else{
                $cust_id = Survey::find($request->survey_id)->cust_id;
                $install_items = SurveyInstallItem::where('survey_id',$request->survey_id)->update([
                    'is_install'=>1
                ]);
                $cust_tsh = Customer::find($cust_id)->tsh_id;
                $surveys = new Survey();
                $surveys = $surveys->leftJoin('customers','customers.id','=','surveys.cust_id')->where('surveys.is_solve',1)->where('customers.tsh_id',$cust_tsh)->get();
                $count = $surveys->count();
                $cust_count = str_pad(++$count,4,"0",STR_PAD_LEFT);
                $tsh_short_code = Township::find($cust_tsh)->townshort_name;
                $voucher_no = $tsh_short_code.$cust_count;
                // dd($voucher_no);
                $solve_survey = Survey::find($request->survey_id)->update([
                    'c_code'=>$voucher_no,
                    'is_solve'=>$request->is_solve,
                ]);
                

                $assigns = Assign::where('survey_id',$request->survey_id)->update([
                    'solved_date'=>date('Y-m-d H:i:s'),
                    'is_solve'=>1,
                    'solved_by'=>$request->solved_by
                ]);

                
                if ($request->photos != null) {
                    
                    foreach ($request->photos as $key => $image) {
                        // dd($image);
                        $date = Carbon::now();
                        $timeInMilliseconds = $date->getPreciseTimestamp(3);
               
                        $destinationPath = public_path() . '/uploads/survey/';

                        if(!File::isDirectory($destinationPath)){
                            File::makeDirectory($destinationPath, 0777, true, true);
                        }
                        $photo = "";
                        //upload image
                        if ($file = $image) {
                            
                            $extension = $file->getClientOriginalExtension();

                            $input['imagename'] = 'img'.$timeInMilliseconds.$key.'.' . $extension;
                            $img = Image::make($image->getRealPath());
                            $img->orientate()
                                ->fit(800, 800, function ($constraint) {
                                    $constraint->upsize();
                                })->save($destinationPath.'/'.$input['imagename']);
                            $photo = $input['imagename'];
                        }

                        $image = Photo::create([
                            'is_survey'=>$request->is_survey,
                            'survey_id'=>$request->survey_id,
                            'photo_name'=>$photo,
                            'path'=>'uploads/survey/',
                        ]);
                      
                        }
                    }

                    // //signature
                    //  $imageName='';
                    // if ($request->cust_sign){
                    //     $image = $request->input('cust_sign'); // image base64 encoded
                    //     preg_match("/data:image\/(.*?);/",$image,$image_extension); // extract the image extension
                    //     $image = preg_replace('/data:image\/(.*?);base64,/','',$image); // remove the type part
                    //     $image = str_replace(' ', '+', $image);
                    //     $imageName = 'cust_'.time() . '.' . $image_extension[1]; //generating unique file name;
                    //     \File::put( public_path(). '/uploads/survey_sign/'.$imageName,base64_decode($image));
                    // }

                    // $tech_sign_photo = ""; 
                    // if ($request->tech_sign){
                    //     $tech_image = $request->input('tech_sign'); // image base64 encoded
                    //     preg_match("/data:image\/(.*?);/",$tech_image,$image_extension); // extract the image extension
                    //     $tech_image = preg_replace('/data:image\/(.*?);base64,/','',$tech_image); // remove the type part
                    //     $tech_image = str_replace(' ', '+', $tech_image);
                    //     $tech_sign_photo = 'tech_'.time() . '.' . $image_extension[1]; //generating unique file name;
                    //     // dd($tech_sign_photo);
                    //     \File::put( public_path().'/uploads/survey_sign/'.$tech_sign_photo,base64_decode($tech_image));
                    // }

                    // $signature = Signature::create([
                    //     'survey_id'=> $request->survey_id,
                    //     'path' => '/uploads/survey_sign/',
                    //     'cust_sign'=>$imageName,
                    //     'tech_sign'=>$tech_sign_photo,
                    // ]);

                    $date = Carbon::now();
                    $timeInMilliseconds = $date->getPreciseTimestamp(3);
           
                    $destinationPath = public_path() . '/uploads/survey_sign';

                    $cust_sign = "";
                        //upload image
                    if ($file = $request->file('cust_sign')) {
                        
                        $extension = $file->getClientOriginalExtension();

                        $input['imagename'] = 'cust_sign'.$timeInMilliseconds.'.' . $extension;
                        $img = Image::make($request->file('cust_sign')->getRealPath());
                        $img->orientate()
                            ->fit(800, 800, function ($constraint) {
                                $constraint->upsize();
                            })->save($destinationPath.'/'.$input['imagename']);
                        $cust_sign = $input['imagename'];
                    }

                    $tech_sign = "";
                        //upload image
                    if ($file = $request->file('tech_sign')) {
                        
                        $extension = $file->getClientOriginalExtension();

                        $input['imagename'] = 'tech_sign'.$timeInMilliseconds.'.' . $extension;
                        $img = Image::make($request->file('tech_sign')->getRealPath());
                        $img->orientate()
                            ->fit(800, 800, function ($constraint) {
                                $constraint->upsize();
                            })->save($destinationPath.'/'.$input['imagename']);
                        $tech_sign = $input['imagename'];
                    }

                    // $cust_sign_image = "";
                    //     //upload image
                    // if ($file = $request->file('cust_sign_image')) {
                    //     $extension = $file->getClientOriginalExtension();

                    //     $input['imagename'] = 'cust_sign_image'.$timeInMilliseconds.'.' . $extension;
                    //     $img = Image::make($request->file('cust_sign_image')->getRealPath());
                    //     $img->orientate()
                    //         ->fit(800, 800, function ($constraint) {
                    //             $constraint->upsize();
                    //         })->save($destinationPath.'/'.$input['imagename']);
                    //     $cust_sign_image = $input['imagename'];
                    // }

                    //upload image
                    $cust_sign_image = "";
                    if ($file = $request->file('cust_sign_image')) {

                        $cust_sign_image = $request->file('cust_sign_image');
                        $ext = '.' . $request->cust_sign_image->getClientOriginalExtension();
                        $fileName = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->cust_sign_image->getClientOriginalName());
                        $file->move($destinationPath, $fileName);
                        $cust_sign_image = $fileName;
                    }

                    $tech_sign_image = "";
                    if ($file = $request->file('tech_sign_image')) {

                        $tech_sign_image = $request->file('tech_sign_image');
                        $ext = '.' . $request->tech_sign_image->getClientOriginalExtension();
                        $fileName = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->tech_sign_image->getClientOriginalName());
                        $file->move($destinationPath, $fileName);
                        $tech_sign_image = $fileName;
                    }


                    // $tech_sign_image = "";
                    //     //upload image
                    // if ($file = $request->file('tech_sign_image')) {
                        
                    //     $extension = $file->getClientOriginalExtension();

                    //     $input['imagename'] = 'tech_sign_image'.$timeInMilliseconds.'.' . $extension;
                    //     $img = Image::make($request->file('tech_sign_image')->getRealPath());
                    //     $img->orientate()
                    //         ->fit(800, 800, function ($constraint) {
                    //             $constraint->upsize();
                    //         })->save($destinationPath.'/'.$input['imagename']);
                    //     $tech_sign_image = $input['imagename'];
                    // }


                $signature = Signature::create([
                    'survey_id'=> $request->survey_id,
                    'path' => '/uploads/survey_sign/',
                    'cust_sign'=>$cust_sign,
                    'tech_sign'=>$tech_sign,
                    'cust_sign_image'=>$cust_sign_image,
                    'tech_sign_image'=>$tech_sign_image
                ]);

                return response([
                    'message'=>"Success",
                    'status'=>1
                ]);
          }
    }

    public function new_customer_list(Request $request)
    {
       $input = $request->all();
           $rules=[
                'team_id'=>'required',
                'page'=>'required',
          ];
          $validator = Validator::make($input, $rules);
          $response = array('response' => '', 'success'=>false);
           if ($validator->fails()) {
              $messages = $validator->messages();
                  return response([
                    'message'=>$validator->messages()->first(),
                    'status'=>0,
                ]);
          }else{

            $assigns = new Assign();
            $assigns = $assigns->leftJoin('surveys',function($join){
                                $join->on('assigns.survey_id', '=', 'surveys.id');
                            })
                            ->leftjoin('groups','groups.id','=','assigns.team_id')
                            ->leftjoin('customers','customers.id','=','surveys.cust_id')
                            ->leftjoin('townships','townships.id','=','customers.tsh_id')
                            ->select([
                                'surveys.id',
                                'surveys.lat',
                                'surveys.lng',
                                'customers.name',
                                'customers.phone_no',
                                'townships.town_name',
                                'customers.address',
                                'surveys.is_solve',
                                'surveys.created_at'
                            ])->where('survey_id','!=',null)->where('assigns.team_id',$request->team_id)->whereDate('assigns.assign_date','=',date('Y-m-d'))->where('assigns.is_solve',0);
            if ($request->keyword != null) {
                $assigns = $assigns->where('customers.name','like','%'.$request->keyword.'%')->orwhere('customers.phone_no','like','%'.$request->keyword.'%');
            }

            $assigns = $assigns->orderBy('surveys.created_at','DESC')->paginate(10);

            return response([
                    'message'=>"Success",
                    'status'=>1,
                    'new_list'=>$assigns
                ]);
          }
    }

    public function left_customer_list(Request $request)
    {

        $input = $request->all();
           $rules=[
                'team_id'=>'required',
                'page'=>'required',
                'admin_status'=>'required'
          ];
          $validator = Validator::make($input, $rules);
          $response = array('response' => '', 'success'=>false);
           if ($validator->fails()) {
              $messages = $validator->messages();
                  return response([
                    'message'=>$validator->messages()->first(),
                    'status'=>0,
                ]);
          }else{
            if ($request->admin_status == 1) {
                $assigns = new Assign();
                $assigns = $assigns->leftJoin('surveys',function($join){
                                    $join->on('assigns.survey_id', '=', 'surveys.id');
                                })
                                ->leftjoin('groups','groups.id','=','assigns.team_id')
                                ->leftjoin('customers','customers.id','=','surveys.cust_id')
                                ->leftjoin('townships','townships.id','=','customers.tsh_id')
                                ->select([
                                    'surveys.id',
                                    'surveys.lat',
                                    'surveys.lng',
                                    'customers.name',
                                    'customers.phone_no',
                                    'townships.town_name',
                                    'customers.address',
                                    'surveys.is_solve',
                                    'surveys.created_at',
                                    'assigns.assign_date'
                                ])->where('survey_id','!=',null)->where('assigns.is_solve',0)->where('assigns.team_id',$request->team_id);
               
                if ($request->keyword != null) {
                    $assigns = $assigns->where('customers.name','like','%'.$request->keyword.'%')->orwhere('customers.phone_no','like','%'.$request->keyword.'%');
                }

                $assigns = $assigns->orderBy('surveys.created_at','DESC')->paginate(10);
                 return response([
                        'message'=>"Success",
                        'status'=>1,
                        'new_list'=>$assigns
                    ]);
            }else{
                $assigns = new Assign();
                $assigns = $assigns->leftJoin('surveys',function($join){
                                    $join->on('assigns.survey_id', '=', 'surveys.id');
                                })
                                ->leftjoin('groups','groups.id','=','assigns.team_id')
                                ->leftjoin('customers','customers.id','=','surveys.cust_id')
                                ->leftjoin('townships','townships.id','=','customers.tsh_id')
                                ->select([
                                    'surveys.id',
                                    'surveys.lat',
                                    'surveys.lng',
                                    'customers.name',
                                    'customers.phone_no',
                                    'townships.town_name',
                                    'customers.address',
                                    'surveys.is_solve',
                                    'surveys.created_at',
                                    'assigns.assign_date'
                                ])->where('survey_id','!=',null)->where('assigns.is_solve',0)->where('assigns.team_id',$request->team_id)->whereDate('assigns.assign_date','!=',date('Y-m-d'));
               
                if ($request->keyword != null) {
                    $assigns = $assigns->where('customers.name','like','%'.$request->keyword.'%')->orwhere('customers.phone_no','like','%'.$request->keyword.'%');
                }

                $assigns = $assigns->orderBy('surveys.created_at','DESC')->paginate(10);
                 return response([
                        'message'=>"Success",
                        'status'=>1,
                        'new_list'=>$assigns
                    ]);
            }
            
          }
    }

    public function install_customer_list(Request $request)
    {
        $input = $request->all();
           $rules=[
                'team_id'=>'required',
                'page'=>'required',
          ];
          $validator = Validator::make($input, $rules);
          $response = array('response' => '', 'success'=>false);
           if ($validator->fails()) {
              $messages = $validator->messages();
                 return response([
                    'message'=>$validator->messages()->first(),
                    'status'=>0,
                ]);
          }else{
            $assigns = new Assign();
            $assigns = $assigns->leftJoin('surveys',function($join){
                                $join->on('assigns.survey_id', '=', 'surveys.id');
                            })
                            ->leftjoin('groups','groups.id','=','assigns.team_id')
                            ->leftjoin('customers','customers.id','=','surveys.cust_id')
                            ->leftjoin('townships','townships.id','=','customers.tsh_id')
                            ->select([
                                'surveys.id',
                                'surveys.lat',
                                'surveys.lng',
                                'customers.name',
                                'customers.phone_no',
                                'townships.town_name',
                                'customers.address',
                                'surveys.is_solve',
                                'assigns.solved_date',
                                'assigns.admin_check',
                                'assigns.checked_by',
                                'surveys.c_code'
                            ])->where('survey_id','!=',null)->where('assigns.is_solve',1)->where('assigns.team_id',$request->team_id)->where('survey_type',1);
                            
            if ($request->keyword != null) {
                $assigns = $assigns->where('customers.name','like','%'.$request->keyword.'%')->orwhere('customers.phone_no','like','%'.$request->keyword.'%')->orwhere('surveys.c_code','like','%'.$request->keyword.'%');
            }
            
            if ($request->from_date != null && $request->to_date != null) {
                $from_date = date('Y-m-d',strtotime($request->from_date))." 00:00:00";
                $to_date = date('Y-m-d',strtotime($request->to_date))." 23:59:59";

                // dd($from_date,$to_date);
                $assigns = $assigns->whereBetween('assigns.assign_date',[$from_date,$to_date]);
            }
            $assigns = $assigns->orderBy('surveys.created_at','DESC')->paginate(10);
             return response([
                    'message'=>"Success",
                    'status'=>1,
                    'new_list'=>$assigns
                ]);
          }
    }

    public function survey_history_detail($id)
    {
        $survey = new Survey();
        $survey = $survey->leftjoin('customers','customers.id','=','surveys.cust_id')
                        ->leftJoin('townships','townships.id','=','customers.tsh_id')
                        ->select([
                            'surveys.*',
                            'customers.name',
                            'customers.phone_no',
                            'customers.address',
                            'townships.town_name'
                        ])->find($id);
        $survey_photos = Photo::where('survey_id',$id)->where('is_survey',1)->get();
        $install_photos = Photo::where('survey_id',$id)->where('is_survey',0)->get();
        $assign = new Assign();
        
        $survey_install_items = new SurveyInstallItem();

        $survey_install_items = $survey_install_items->leftJoin('items','items.id','survey_install_items.item_id')->leftjoin('categories','categories.id','=','survey_install_items.cat_id')->leftjoin('brands','brands.id','=','items.brand_id')
        ->select([
            'items.id',
            'items.model',
            'items.unit',
            'survey_install_items.qty',
            'survey_install_items.item_price AS price',
            'survey_install_items.amount',
            'categories.name AS cat_name',
            'brands.name AS brand_name',
            'items.cat_id',
            'items.brand_id'
            
        ])->where('survey_id',$id)->get();
        $signatures = Signature::where('survey_id',$id)->first();
        $amounts = Amount::where('survey_id',$id)->first();
        return response([
                    'message'=>"Success",
                    'status'=>1,
                    'customer_data'=>$survey,
                    'survey_photos'=>$survey_photos,
                    'install_photos'=>$install_photos,
                    'survey_items'=>$survey_install_items,
                    'amount'=>$amounts,
                    'signatures'=>$signatures
                ]);
    }

    public function survey_create(Request $request)
    {
        // dd($request->all());
        $input = $request->all();
           $rules=[
                'name' => 'required',
                'phone_no' => 'required',
                'tsh_id' => 'required',
                'address'=>'required',
                'lat'=>'required',
                'lng'=>'required',
                'survey_by'=>'required'
          ];
          $validator = Validator::make($input, $rules);
          $response = array('response' => '', 'success'=>false);
           if ($validator->fails()) {
              $messages = $validator->messages();
                  return response([
                    'message'=>$validator->messages()->first(),
                    'status'=>0,
                ]);
          }else{
            DB::beginTransaction();
            try {
                $customer = Customer::create([
                    'name'=>$request->name,
                    'phone_no'=>$request->phone_no,
                    'tsh_id'=>$request->tsh_id,
                    'address'=>$request->address,
                ]);
                
                $user = User::where('group_id',$request->survey_by)->first();

                $survey_name = Group::find($user->group_id);
                if ($survey_name != null) {
                    $survey_name = Group::find($request->survey_by)->group_name;
                }else{
                    $survey_name = $user->name;
                }
                $survey = Survey::create([
                    'cust_id'=>$customer->id,
                    'survey_by'=>$request->survey_by,
                    'survey_name'=>$survey_name,
                    'lat'=>$request->lat,
                    'lng'=>$request->lng,
                    'remark'=>$request->remark,
                    'survey_type'=>1,
                    'archive_status'=>1
                ]);
                $amount = Amount::create([
                    'survey_id'=>$survey->id,
                    'total_amt'=>$request->total_amt,
                    'sub_total'=>$request->sub_total,
                    'install_charge'=>$request->install_charge,
                    'is_cloud'=>$request->is_cloud,
                    'cloud_charge'=>$request->cloud_charge,
                    'discount'=>$request->discount,
                    'cabling_charge'=>$request->cabling_charge
                ]);
                foreach ($request->item as $key => $value) {
                    $install_items = SurveyInstallItem::create([
                        'survey_id'=>$survey->id,
                        'item_id'=>$value['id'],
                        'item_price'=>$value['price'],
                        'cat_id'=>$value['cat_id'],
                        'qty'=>$value['qty'],
                        'amount'=>$value['amount'],
                        'is_install'=>1
                    ]);
                }

                $assign = Assign::create([
                        'survey_id'=>$survey->id,
                        'team_id'  => $request->team_id != null ? $request->team_id : null,
                        'assign_date'=>$request->team_id != null ? date('Y-m-d',strtotime($request->assign_date)).' '.$hour : null,
                        'appoint_date'=>$request->team_id != null ? date('Y-m-d',strtotime($request->appoint_date)) : null
                      ]);

                DB::commit();
                return response([
                    'message'=>"Success",
                    'status'=>1,
                    'survey_id'=>$survey->id
                    
                ]);
            } catch (Exception $e) {
               
                DB::rollback();
                 return response([
                    'message'=>"Something wrong!",
                    'status'=>1,
                    
                ]);
            }
            

          }
    }

    public function admin_survey_list(Request $request)
    {
        $input = $request->all();
           $rules=[
                'page'=>'required',
          ];
          $validator = Validator::make($input, $rules);
          $response = array('response' => '', 'success'=>false);
           if ($validator->fails()) {
              $messages = $validator->messages();
                  return response([
                    'message'=>$validator->messages()->first(),
                    'status'=>0,
                ]);
          }else{
            $assigns = new Survey();
            $assigns = $assigns->leftJoin('customers','customers.id','=','surveys.cust_id')
                                ->leftjoin('townships','townships.id','=','customers.tsh_id')
                                ->select([
                                    'surveys.id',
                                    'surveys.lat',
                                    'surveys.lng',
                                    'customers.name',
                                    'customers.phone_no',
                                    'townships.town_name',
                                    'customers.address',
                                    'surveys.is_solve',
                                    'surveys.created_at',
                                    'surveys.admin_check',
                                    'surveys.checked_by',
                                    'surveys.assign_status'
                                ]);

            if ($request->keyword != null) {
                $assigns = $assigns->where('customers.name','like','%'.$request->keyword.'%')->orwhere('customers.phone_no','like','%'.$request->keyword.'%');
            }
            if ($request->from_date != null && $request->to_date != null) {
                $from_date = date('Y-m-d',strtotime($request->from_date))." 00:00:00";
                $to_date = date('Y-m-d',strtotime($request->to_date))." 23:59:59";

                // dd($from_date,$to_date);
                $assigns = $assigns->whereBetween('surveys.created_at',[$from_date,$to_date]);
            }
            $assigns = $assigns->orderBy('surveys.created_at','DESC')->paginate(10);

            return response([
                    'message'=>"Success",
                    'status'=>1,
                    'survey_history'=>$assigns
                ]);
          }
    }

    public function admin_installed_list(Request $request)
    {
        $assigns = new Assign();
        $assigns = $assigns->leftJoin('surveys',function($join){
                            $join->on('assigns.survey_id', '=', 'surveys.id');
                        })
                        ->leftjoin('groups','groups.id','=','assigns.team_id')
                        ->leftjoin('customers','customers.id','=','surveys.cust_id')
                        ->leftjoin('townships','townships.id','=','customers.tsh_id')
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
                        ])->where('surveys.is_solve',1);
            ;
        
        if ($request->keyword != '') {
            $assigns = $assigns->where('customers.name','like','%'.$request->keyword.'%');
        }
        if ($request->tsh_id != null) {
            $assigns = $assigns->where('tsh_id',$request->tsh_id);
        }
 
        if ($request->team_id != null) {
            $assigns = $assigns->where('team_id',$request->team_id);
        }

       

        if ($request->from_date != null && $request->to_date != null) {
            $from_date = date('Y-m-d',strtotime($request->from_date))." 00:00:00";
            $to_date = date('Y-m-d',strtotime($request->to_date))." 23:59:59";

            $assigns = $assigns->whereBetween('assigns.solved_date',[$from_date,$to_date]);

            // dd($assigns->get()->count());
        }

        $assigns = $assigns->orderBy('assigns.solved_date','DESC')->paginate(10);

            return response([
                    'message'=>"Success",
                    'status'=>1,
                    'install_list'=>$assigns
                ]);
    }

    public function admin_assign_team(Request $request)
    {
        $input = $request->all();
        $rules=[
                'status'=>'required',
        ];
        $validator = Validator::make($input, $rules);
        $response = array('response' => '', 'success'=>false);
        if ($validator->fails()) {
              $messages = $validator->messages();
                  return response([
                    'message'=>$validator->messages()->first(),
                    'status'=>0,
                ]);
          }else{
            if ($request->status == 1) {
                $survey = Survey::find($request->survey_id)->update([
                        'assign_status'=>$request->team_id != null ? 1 : 0,
                        'remark'=>$request->remark
                    ]);
                $assign = Assign::where('survey_id',$request->survey_id)->first();
                $hour = date('H:i:s');
                if ($request->team_id != null && $assign == null) {
                    $assign = Assign::create([
                    'survey_id'=>$id,
                    'team_id'  => $request->team_id,
                    'assign_date'=>date('Y-m-d',strtotime($request->assign_date)).' '.$hour,
                    'appoint_date'=>date('Y-m-d',strtotime($request->appoint_date))
                  ]);
                }else{
                    $assign = $assign->update([
                        'team_id'  => $request->team_id,
                        'assign_date'=>date('Y-m-d').' '.$hour,
                        'appoint_date'=>date('Y-m-d')
                    ]);
                }
                $survey_install_count = SurveyInstallItem::where('survey_id',$request->survey_id)->get()->count();
                if ($survey_install_count != 0) {
                    $survey_install_items = SurveyInstallItem::where('survey_id',$request->survey_id)->delete();
                }
                if (count($request->item) != 0) {
                    foreach ($request->item as $key => $value) {
                        $install_items = SurveyInstallItem::create([
                                        'survey_id'=>$request->survey_id,
                                        'item_id'=>$value['id'],
                                        'item_price'=>$value['price'],
                                        'cat_id'=>$value['cat_id'],
                                        'qty'=>$value['qty'],
                                        'amount'=>$value['amount'],
                                        'is_install'=>1
                                    ]);
                    }
                    
                }
                $total_amt = Amount::where('survey_id',$request->survey_id)->first();

                $total_amt = Amount::where('survey_id',$request->survey_id)->first();
                if ($total_amt != null) {
                        $total_amt = $total_amt->update([
                        'sub_total'=>$request->sub_total,
                        'total_amt'=>$request->total_amt,
                        'install_charge'=>$request->install_charge,
                        'is_cloud'=>$request->cloud_charge == null ? 0 : 1,
                        'cloud_charge'=>$request->cloud_charge == null ? 0 : $request->cloud_charge,
                        'cabling_charge'=>$request->cabling_charge == null ? 0 : $request->cabling_charge,
                        'discount'=>$request->discount == null ? 0 : $request->discount
                    ]);
                }else{
                        $total_amt = Amount::create([
                            'survey_id'=>$id,
                            'sub_total'=>$request->sub_total,
                            'total_amt'=>$request->total_amt,
                            'install_charge'=>$request->install_charge,
                            'is_cloud'=>$request->cloud_charge == null ? 0 : 1,
                            'cloud_charge'=>$request->cloud_charge == null ? 0 : $request->cloud_charge,
                            'cabling_charge'=>$request->cabling_charge == null ? 0 : $request->cabling_charge,
                            'discount'=>$request->discount == null ? 0 : $request->discount
                        ]);
                    }

            }else{
                $survey = Survey::find($request->survey_id)->update([
                        'assign_status'=>$request->team_id != null ? 1 : 0,
                        'remark'=>$request->remark
                    ]);
                $assign = Assign::where('survey_id',$request->survey_id)->first();
                $hour = date('H:i:s');
                if ($request->team_id != null && $assign == null) {
                    $assign = Assign::create([
                        'survey_id'=>$id,
                        'team_id'  => $request->team_id,
                        'assign_date'=>date('Y-m-d').' '.$hour,
                        'appoint_date'=>date('Y-m-d')
                      ]);
                    }else{
                        $assign = $assign->update([
                            'team_id'  => $request->team_id,
                            'assign_date'=>date('Y-m-d').' '.$hour,
                            'appoint_date'=>date('Y-m-d')
                        ]);
                }
            }
        }
    return response([
                    'message'=>"Success",
                    'status'=>1,
                   
                ]);

    }

}
