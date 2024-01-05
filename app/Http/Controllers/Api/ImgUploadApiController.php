<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ticket;
use App\Photo;
use Validator;
use Carbon\Carbon;
use Image;
use File;

class ImgUploadApiController extends Controller
{
	public function img_upload(Request $request)
	{
		$input = $request->all();
           $rules=[
                'ticket_id'=>'required',
                'photos'=>'required',
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
          		$date = Carbon::now();
                $timeInMilliseconds = $date->getPreciseTimestamp(3);
               
                $destinationPath = public_path() . '/uploads/ticket/';
                
                if ($request->photos != null) {
                    
                    foreach ($request->photos as $key => $image) {
                        // dd($image);
                        $date = Carbon::now();
                        $timeInMilliseconds = $date->getPreciseTimestamp(3);
               
                        $destinationPath = public_path() . '/uploads/ticket/';

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
                            'ticket_id'=>$request->ticket_id,
                            'photo_name'=>$photo,
                            'path'=>'uploads/ticket/',
                        ]);
                      
                        }
                }
                return response([
	                'message'=>"Success",
	                'status'=>1,
	            ]);
          }
	}

    public function survey_img_upload(Request $request)
    {
        $input = $request->all();
           $rules=[
                'survey_id'=>'required',
                'photos'=>'required',
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
                $date = Carbon::now();
                $timeInMilliseconds = $date->getPreciseTimestamp(3);
               
                $destinationPath = public_path() . '/uploads/survey/';
                
                if ($request->photos != null) {
                    
                    foreach ($request->photos as $key => $image) {
                        
                        $date = Carbon::now();
                        $timeInMilliseconds = $date->getPreciseTimestamp(3);
               
                        $destinationPath = public_path() . '/uploads/survey';

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
                return response([
                    'message'=>"Success",
                    'status'=>1,
                ]);
          }
    }
}