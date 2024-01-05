<?php

namespace App;


use GuzzleHttp\Client;

/**
* class SMSGO to send SMS on Mobile Numbers.
* @author aungsoeoo
*/

class SMSGO 
{
    public function getAccessToken()
    {
     	$isError = 0;
        $errorMessage = true;	
    	$client = new \GuzzleHttp\Client();
	    $url = "http://service.smsgo.com.mm/api/token";
	   
	    $postBody =[
			"BrandId"=>'df3e05b7-8e2c-4629-b950-2248f92498cd',
			"APIKey"=>"47897424-90e4-4c63-8793-5f70045f947c-23754f3a-e75e-48b3-b5d1-4947fc7cc203" 
    	];

    	$header = [
    		'content-type' => 'application/json',
    		'Accept' => 'application/json'
    	];

    	try {
		    $response = $client->post($url, [
		        'headers' => $header,
		        'body' => json_encode($postBody),
		    ]);

		    $statusCode = $response->getStatusCode(); // 200
			$responseBody =json_decode($response->getBody());

			$access_token = $responseBody->result->access_token;

			return $access_token;

		} catch (GuzzleException $exception) {
		    $response['error'] = 1;
            $response['message'] = "Something went wrong!";
            return $response;
		}

    }



    
    
    public function sendSMS($otp,$mobileNumber)
    {
    	// dd($mobileNumber);

        // $isError = 0;
        // $errorMessage = true;
    	$token = $this->getAccessToken();
 		
    	$client = new \GuzzleHttp\Client();
	    $url = "http://smsapi.linnsoft.xyz/api/sendOtp";
	   	// dd($url);
	   	$content = (string) $otp ." is your OTP code for CCTV";
	    $postBody =[
			// "Content"=>$content,
			// "PhoneNos"=>[$mobileNumber] 
			"phone" => $mobileNumber,
            "message" => $content,
    	];

    	$auth_token =  'Bearer '.$token;

    	$header = [
    		'content-type' => 'application/json',
    		'Authorization' => $auth_token
    	];

    	try {
		    $response = $client->post($url, [
		        'headers' => $header,
		        'body' => json_encode($postBody),
		    ]);

		    // dd($response);

		    $statusCode = $response->getStatusCode(); // 200
			$responseBody =json_decode($response->getBody());
			return $responseBody;

		} catch (GuzzleException $exception) {
		    $response['error'] = 1;
            $response['message'] = "Something went wrong!";

            return $response;
		}

    }


     public function sendApproveSMS($msg,$mobileNumber)
    {

        // $isError = 0;
        // $errorMessage = true;
    	$token = $this->getAccessToken();
 		
    	$client = new \GuzzleHttp\Client();
	    $url = "http://service.smsgo.com.mm/api/sms/v1";
	   
	   	$content = (string) $msg;
	    $postBody =[
			"Content"=>$content,
			"PhoneNos"=>[$mobileNumber] 
    	];

    	$auth_token =  'Bearer '.$token;

    	$header = [
    		'content-type' => 'application/json',
    		'Authorization' => $auth_token
    	];

    	try {
		    $response = $client->post($url, [
		        'headers' => $header,
		        'body' => json_encode($postBody),
		    ]);

		    $statusCode = $response->getStatusCode(); // 200
			$responseBody =json_decode($response->getBody());
			return $responseBody;

		} catch (GuzzleException $exception) {
		    $response['error'] = 1;
            $response['message'] = "Something went wrong!";

            return $response;
		}

    }


}
