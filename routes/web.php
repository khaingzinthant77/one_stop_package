<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('dashboard');
    return redirect()->route('package_dashboard');
})->middleware('auth');
Route::get('/home', function () {
    // return view('dashboard');
    return redirect()->route('dashboard');
})->middleware('auth');

Route::group(['middleware' => 'auth'], function () {

    //main dashboard
    Route::get('dashboard', 'HomeController@index')->name('dashboard');

    //daily dashboard
    Route::get('daily_dashboard', 'HomeController@daily_dashboard')->name('daily_dashboard');
    //group
    Route::resource('group', 'GroupController');

    //technician
    Route::resource('technician', 'TechnicianController');

    //change status technician
    Route::get('change_status_tech', 'TechnicianController@change_status_tech')->name('change_status_tech');

    //team leader
    Route::resource('team_leaders', 'TeamLeaderController');

    //get technician according to team
    Route::post('select_team', 'TeamLeaderController@select_team')->name('select_team');

    //get technician according to group and team leader
    Route::get('get_technicians', 'TeamLeaderController@get_technicians')->name('get_technicians');

    //townships
    Route::resource('township', 'TownshipController');

    //Cloud Service Charge
    Route::resource('servicecharge', 'ServiceChargeController');

    //categories
    Route::resource('category', 'CategoryController');

    //category import
    Route::post('categoryimport', 'CategoryController@categoryimport')->name('categoryimport');

    //category export
    Route::get('categoryexport', 'CategoryController@categoryexport')->name('categoryexport');

    //category status change
    Route::get('change_status_category', 'CategoryController@change_status_category')->name('change_status_category');

    //brand
    Route::resource('brand', 'BrandController');

    //brand import
    Route::post('brandimport', 'BrandController@brandimport')->name('brandimport');

    //brand export
    Route::get('brandexport', 'BrandController@brandexport')->name('brandexport');

    //survey export
    Route::post('survey_export', 'SurveyController@survey_export')->name('survey_export');

    //product
    Route::resource('item', 'ItemController');

    //camera report
    Route::get('camera_report','ItemController@camera_report')->name('camera_report');

    //product import
    Route::post('import', 'ItemController@import')->name('import');

    //product export
    Route::get('export', 'ItemController@export')->name('export');

    //select category with autocomplete
    Route::get('search_category', 'CategoryController@search_category')->name('search_category');

    //select brand with autocomplete
    Route::get('search_brand', 'BrandController@search_brand')->name('search_brand');

    //survey
    Route::resource('surveys', 'SurveyController');

    //not_install_update
    Route::post('not_install_update','SurveyController@not_install_update')->name('surveys.not_install_update');

    //get install amt
    Route::get('get_install_amt', 'SurveyController@get_install_amt')->name('get_install_amt');

    //get cloud charge
    Route::get('get_cloud_charge', 'SurveyController@get_cloud_charge')->name('get_cloud_charge');

    //get category price
    Route::get('get_cat_price', 'SurveyController@get_cat_price')->name('get_cat_price');

    //delete photo
    Route::get('delete_img', 'SurveyController@delete_img')->name('delete_img');

    //customer
    Route::resource('customer', 'CustomerController');

    //ticket
    Route::resource('ticket', 'TicketController');

    //ticket amount report
    Route::get('ticket_report','TicketController@ticket_report')->name('ticket_report');

    //ticket report
    Route::post('service_report_export','TicketController@service_report_export')->name('service_report_export');

    //customer autocomplete search
    Route::get('customer_search', 'CustomerController@customer_search')->name('customer_search');

    //issue_type
    Route::resource('issue_type', 'IssueTypeController');

    //change status brand
    Route::get('change_status_brand', 'BrandController@change_status_brand')->name('change_status_brand');

    // change_status_issue
    Route::get('change_status_issue', 'IssueTypeController@change_status_issue')->name('change_status_issue');

    // change show dashboard status
    Route::get('change_status_show', 'CategoryController@change_status_show')->name('change_status_show');

    //warranty period
    Route::resource('warranty_period', 'WarrantyPeriodController');

    //customer print
    Route::get('/print/{id}', 'CustomerController@print')->name('customer.print');

    //customer map
    Route::get('customer_map', 'CustomerController@customer_map')->name('customer_map');

    //customer export
    Route::post('customer_export', 'CustomerController@customer_export')->name('customer_export');

    //customerimport
    Route::post('customer_import', 'CustomerController@customer_import')->name('customer_import');

    //download customer csv file
    Route::get('download_csv', 'CustomerController@download_csv')->name('download_csv');

    //update_admin_check
    Route::get('update_admin_check', 'CustomerController@update_admin_check')->name('update_admin_check');

    //update_survey_check
    Route::get('update_survey_check', 'SurveyController@update_survey_check')->name('update_survey_check');

    //update_ticket_check
    Route::get('update_ticket_check', 'TicketController@update_ticket_check')->name('update_ticket_check');

    //setting
    Route::resource('setting', 'SettingController');

    //setting create
    Route::get('create', 'SettingController@create')->name('setting.create');

    Route::post('update/{id}', 'SettingController@update')->name('setting.update');

    Route::post('service_export', 'TicketController@service_export')->name('service_export');

    Route::get('package_dashboard','HomeController@package_dashboard')->name('package_dashboard');

    Route::get('one_stock_package_customers','CustomerController@package_customers')->name('package_customers');

    //one stop package customer detail
    Route::get('package_cust_detail/{id}','CustomerController@package_cust_detail')->name('package_cust_detail');

    //one stop package export
    Route::post('one_stock_export','CustomerController@one_stock_export')->name('one_stock_export');

    //one stop package create
    Route::get('package_create','CustomerController@package_create')->name('package_create');

    //one stop package store
    Route::post('one_stop_store','CustomerController@one_stop_store')->name('one_stop.store');

    //one stop package customer delete
    Route::delete('package_customer_delete/{id}','CustomerController@package_customer_delete')->name('package_customer.destroy');
});
Auth::routes([
  'register' => false,
  'reset' => false,
  'verify' => false,
]);