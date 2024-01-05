<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['middleware'=>'auth:api'],function(){

	//dashboard
	Route::post('/dashboard','Api\DashboardApiController@get_dashboard_data');

	//get master data
	Route::get('/get_master_data','Api\MasterApiController@get_master_data');

	//ticket master data
	Route::post('ticket_master_data','Api\MasterApiController@ticket_master_data');

	//get history
	Route::post('survey_history','Api\SurveyApiController@survey_history');

	//new customer list
	Route::post('new_customer_list','Api\SurveyApiController@new_customer_list');

	//left customer list
	Route::post('left_customer_list','Api\SurveyApiController@left_customer_list');

	//install customer list
	Route::post('install_customer_list','Api\SurveyApiController@install_customer_list');

	//unsolve list
	Route::post('unsolve_list','Api\TicketApiController@unsolve_list');

	//unsolve detail
	Route::post('unsolve_detail','Api\TicketApiController@unsolve_detail');

	//solve list
	Route::post('solve_list','Api\TicketApiController@solve_list');

	//survey history detail
	Route::get('survey_history_detail/{survey_id}','Api\SurveyApiController@survey_history_detail');

	//ticket list detail
	Route::get('ticket_detail/{ticket_id}','Api\TicketApiController@ticket_detail');

	//solve survey
	Route::post('solve_survey','Api\SurveyApiController@solve_survey');

	//solve ticket
	Route::post('solve_ticket','Api\TicketApiController@solved_ticket');

	//ticket aggrement
	Route::post('ticket_aggrement','Api\TicketApiController@ticket_aggrement');

	//survey create
	Route::post('survey_create','Api\SurveyApiController@survey_create');

	//img upload
	Route::post('img_upload','Api\ImgUploadApiController@img_upload');

	//survey img upload
	Route::post('survey_img_upload','Api\ImgUploadApiController@survey_img_upload');

	//model list
	Route::post('models','Api\ModelApiController@get_all_models');
	
	//other customer create
	Route::post('other_create','Api\TicketApiController@other_create');

	//ticket create 
	Route::post('ticket_create','Api\TicketApiController@ticket_create');
	
	//get exit customer
	Route::post('exist_customer_list','Api\CustomerApiController@exist_customer_list');

	//customer detail
	Route::get('customer_detail/{survey_id}','Api\CustomerApiController@customer_detail');

	//get team member
	Route::post('team_member','Api\TechnicianApiController@get_team_member');

	//get issue type
	Route::get('issue_types','Api\TicketApiController@issue_type');

	//get all townships
	Route::get('townships','Api\MasterApiController@townships');

	//ticket detail
	Route::post('issue_detail','Api\TicketApiController@issue_detail');

	//admin dashboard
	Route::get('admin_dashboard','Api\DashboardApiController@admin_dashboard');

	//admin survey list
	Route::post('admin_survey_list','Api\SurveyApiController@admin_survey_list');

	//admin installed list
	Route::post('admin_installed_list','Api\SurveyApiController@admin_installed_list');

	//admin solved ticket list
	Route::post('admin_solved_ticket','Api\TicketApiController@admin_solved_ticket');

	//admin assign team
	Route::post('admin_assign_team','Api\SurveyApiController@admin_assign_team');

	//team list
	Route::get('team_list','Api\MasterApiController@team_list');

	//ticket install item
	Route::post('ticket_install_item','Api\TicketApiController@ticket_install_items');

	//one stock package customer create
	Route::post('package_customer_create','Api\CustomerApiController@package_customer_create');

	//one stock package customer list
	Route::post('customer_list','Api\CustomerApiController@customer_list');

	//package customer detail
	Route::get('package_cust_detail','Api\CustomerApiController@package_cust_detail');

	//package list
	Route::get('package_list','Api\CustomerApiController@package_list');

// });

Route::post('/sendOtp','Api\SMSApiController@sendOtp');

Route::post('/verifyOtp','Api\SMSApiController@verifyOtp');
