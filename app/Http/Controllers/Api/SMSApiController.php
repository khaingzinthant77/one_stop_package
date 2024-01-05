<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SMSGO;
use App\User;
use Session;
use Hash;

class SMSApiController extends Controller
{
    /**
    * Sending the OTP.
    *
    * @return Response
    */
    public function sendOtp(Request $request)
    {
    	$response =[];
        $user_phone = User::where('login_id',$request->mobile)->orWhere('email',$request->mobile)->first();
        // dd($user_phone);
        if ($user_phone != null) {

                 if ($user_phone->email == null) {
                    $SMSGO = new SMSGO();

                    $otp = rand(100000, 999999);
                    $checkuser = User::where("login_id",$request->mobile)->get();

                    if($checkuser->count()>0){

                    $user = User::find($checkuser[0]->id);
                    $user = $user->update([
                                'login_id'=>$request->mobile,
                                'password' => Hash::make($otp)
                            ]);
                    }
                    $msgGoResponse = $SMSGO->sendSMS($otp,$request->mobile);
                    // dd($msgGoResponse);
                    if($msgGoResponse->responseCode==0){
                        $response['error'] = 1;
                        $response['message'] = $msgGoResponse->responseMessage;
                        $response['result'] = null;
                    }else{
                        $response['error'] = 0;
                        $response['message'] = $msgGoResponse->responseMessage;
                        $response['result'] = null;
                        $response['OTP'] = $otp;
                    }


                 }else{
                    $response['error'] = 0;
                    $response['message'] = 'Admin Login Success';
                 }            
            }else{
                $response['error'] = 1;
                $response['message'] = 'Phone No is incorrect';
            }



                
        echo json_encode($response);
    }


    public function verifyOtp(Request $request)
    {
        $user_phone = User::where('login_id',$request->loginId)->orWhere('email',$request->loginId)->first();
        // dd($user_phone);
        $phoneno = $request->input('loginId');
        $enteredOtp = $request->input('password');
        // dd($user_phone->email);
        if ($user_phone->email == null) {
            $loginData = [
                'login_id' => $phoneno,
                'password' => $enteredOtp
            ];
             
            // dd($loginData);
            if (!auth()->attempt($loginData)) {
                return response([
                    'message' => 'OTP incorrect!',
                    'status'=>0
                ]);
            }else{
                
                 $accessToken = auth()->user()->createToken('authToken')->accessToken;
                 return response([
                        'user' =>auth()->user(),
                        'access_token' =>$accessToken,
                        'message'=>"Successfully login",
                        'status'=>1
                ]);
            } 
        }else{
            $loginData = [
                'email' => $phoneno,
                'password' => $enteredOtp
            ];

            // dd($loginData);
            if (!auth()->attempt($loginData)) {
                return response([
                    'message' => 'User name or password is incorrect!',
                    'status'=>0
                ]);
            }else{
                 $accessToken = auth()->user()->createToken('authToken')->accessToken;
                 $user = auth()->user();
                 $user->role = 1;
                 return response([
                        'user' =>$user,
                        'access_token' =>$accessToken,
                        'message'=>"Successfully login",
                        'status'=>1
                ]);
            } 
        }

    }


}
