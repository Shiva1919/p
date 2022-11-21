<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SendurlController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\VerficationController;
use App\Http\Controllers\API\PackagesController;
use App\Http\Controllers\API\SerialnoController;
use App\Http\Controllers\API\CustomersController;
use App\Http\Controllers\API\OCFAPIController;
use App\Http\Controllers\API\OCFController;
use App\Http\Controllers\API\OCFCustomerController;
use App\Http\Controllers\API\OCFModuleController;
use App\Http\Controllers\API\OrderConfirmationsController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\UsersController;
use App\Http\Controllers\JWTController;
use App\Http\Controllers\hsnController;

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

Route::group(['middleware' => 'api'], function($router) {
    Route::post('/register', [JWTController::class, 'register']);
    Route::post('/login',    [JWTController::class, 'login']);
    Route::post('/logout',   [JWTController::class, 'logout']);
    Route::post('/refresh',  [JWTController::class, 'refresh']);
    Route::get('/profile',   [JWTController::class, 'profile']);
});

Route::post('logindata', [AuthController::class,'login']);
Route::post('registerdata', [AuthController::class,'register']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});


Route::get('get_hsn/{id}',[hsnController::class,'index']);  // Provide valadation of HSN code

Route::get('loginuser',   [UsersController::class, 'getuserlogin']);         // Auto Login

// Package
Route::resource('package',         PackagesController::class);
Route::get('packagedeactivelist', [PackagesController::class, 'deactivepackageslist']);

// SubPackage
Route::get('subpackage/{packageid}',               [PackagesController::class, 'subpackageindex']);
Route::get('subpackagedeactivelist/{packageid}',   [PackagesController::class, 'deactivesubpackageslist']);
Route::get('subpackagegetbyid/{packageid}/{id}',   [PackagesController::class, 'subpackageshow']);
Route::post('subpackageadd/{packageid}',           [PackagesController::class, 'subpackagestore']);
Route::put('subpackageupdate/{packageid}/{id}',    [PackagesController::class, 'subpackageupdate']);
Route::delete('subpackagedelete/{packageid}/{id}', [PackagesController::class, 'subpackagedelete']);

// Module package id
Route::get('module/{id}',                          [PackagesController::class, 'moduleindex']);
Route::get('moduledeactivelist/{packageid}',       [PackagesController::class, 'deactivemoduleslist']);
Route::get('modulegetbyid/{packageid}/{id}',       [PackagesController::class, 'moduleshow']);
Route::get('moduleid/{id}',                        [PackagesController::class, 'moduleid']);
Route::post('moduleadd/{packageid}',               [PackagesController::class, 'modulestore']);
Route::put('moduleupdate/{packageid}/{id}',        [PackagesController::class, 'moduleupdate']);
Route::delete('moduledelete/{packageid}/{id}',     [PackagesController::class, 'moduledelete']);
Route::get('module',                               [PackagesController::class, 'module']);
Route::get('moduletype',                           [PackagesController::class, 'moduletype']);

Route::get('getmoduledata/{customerid}',           [OCFCustomerController::class, 'getmoduledata']);
Route::get('getmoduletypedata/{moduleid}',         [OCFCustomerController::class, 'getmoduletypedata']);
// Customer
// Route::resource('customer', CustomersController::class);
Route::resource('customer',                         OCFCustomerController::class);
Route::post('customercreate',                      [OCFCustomerController::class, 'customercreate']);
//Company get dependent on customer
Route::get('getcustomer/{customerid}',             [OCFCustomerController::class, 'companybycustomer']);
Route::resource('ocf',                              OCFController::class);
Route::post('OCFstore',                            [OCFController::class, 'OCFstore']);
Route::get('getocfno/{ocfno}',                     [OCFController::class, 'getocfno']);
Route::get('getocf_customer/{customer}',           [OCFController::class, 'getocf_customer']);
Route::get('getocf_modules/{ocf}',                 [OCFController::class, 'getocf_modules']);

Route::get('ocflist',                              [OCFCustomerController::class, 'ocflist']);
Route::get('getocf_customer_company/{id}',         [CustomersController::class, 'ocflist']);


//ocf muliple date get by ocf id
Route::get('getmodaldata/{ocfno}',                 [OCFModuleController::class, 'getocfmodalno']);
Route::resource('ocfmodule',                        OCFModuleController::class);
Route::get('ocfmoduledata/{packageid}',            [OCFModuleController::class, 'indexs']);
//ocf last id
Route::get('getocfid',                             [OCFController::class, 'getocflastid']);
//ocf last series
Route::get('getseries',                            [OCFController::class, 'getocflastseries']);

Route::get('customerdeactivelist',                 [CustomersController::class, 'deactivecustomerslist']);
//Country
// Route::get('state', [CustomersController::class, 'getState']);
// Route::get('district/{stateid}', [CustomersController::class, 'getDistrict']);
// Route::get('taluka/{districtid}', [CustomersController::class, 'getTaluka']);
// Route::get('city/{talukaid}', [CustomersController::class, 'getCity']);

// Branch
Route::get('branch/{customerid}',                 [CustomersController::class, 'branchindex']);
Route::get('branchgetbyid/{customerid}/{id}',     [CustomersController::class, 'branchshow']);
Route::post('branchadd/{customerid}',             [CustomersController::class, 'branchstore']);
Route::put('branchupdate/{customerid}/{id}',      [CustomersController::class, 'branchupdate']);
Route::delete('branchdelete/{customerid}/{id}',   [CustomersController::class, 'branchdelete']);

// Contact
Route::get('contact/{customerid}',                [CustomersController::class, 'contactindex']);
Route::get('contactgetbyid/{customerid}/{id}',    [CustomersController::class, 'contactshow']);
Route::post('contactadd/{customerid}',            [CustomersController::class, 'contactstore']);
Route::put('contactupdate/{customerid}/{id}',     [CustomersController::class, 'contactupdate']);
Route::delete('contactdelete/{customerid}/{id}',  [CustomersController::class, 'contactdelete']);

// Order Confirmation Form
Route::resource('orderconfirmation',               OrderConfirmationsController::class);
Route::delete('orderconfirmationdelete/{id}',     [OrderConfirmationsController::class, 'destroy']);
Route::get('getrefno/{refno}',                    [OrderConfirmationsController::class, 'getrefno']);
Route::get('getcustomer/{customercode}',          [OrderConfirmationsController::class, 'getcustomer']);

// Serialno
// Route::resource('serialno', SerialnoController::class);
Route::post('serialnosendotp',                    [SerialnoController::class, 'serialnosendotp']);
Route::post('serialnoverifyotp',                  [SerialnoController::class, 'serialnoverifyotp']);
Route::post('serialnos',                          [SerialnoController::class, 'serialnogenerate']);
Route::post('ocfchange',                          [SerialnoController::class, 'ocfchange']);

// Get Employee
Route::resource('Employee',                        EmployeeController::class);
Route::get('emp_status/{id}',                     [EmployeeController::class,'status_change']);
// Url Crud
Route::resource('Url',                             UrlController::class);        // For sales call everyday
// fatch State ,District ,Taluka,City,pincode
Route::get('states',                              [SendurlController::class,'getState']);
Route::get('district/{id}',                       [SendurlController::class,'getDistrict']);
Route::get('taluka/{id}',                         [SendurlController::class,'getTaluka']);
Route::get('city/{id}',                           [SendurlController::class,'getCity']);

//Send msg
Route::post('send_msg',                           [SendurlController::class,'send_msg']);

//Fatch Customer Details
Route::get('get_cust_name/{id}',                  [SendurlController::class,'customername']);

//Report
Route::get('datewise/{todate}/{Fromdate}',                               [ReportController::class,'datewise']);
Route::get('date_taluka/{state}/{district}/{taluka}/{todate}/{Fromdate}',[ReportController::class,'fetchalldata_taluka']);
Route::get('date_district/{state}/{district}/{todate}/{Fromdate}',       [ReportController::class,'fetchalldata_district']);
Route::get('date_state/{state}/{todate}/{Fromdate}',                     [ReportController::class,'fetchalldata_state']);

//customer name ,panno,gstno verfication
Route::get('verfication_list',                    [VerficationController::class,'details_changes']);
// Route::get('check_verfication/{company}/{gst}/{pan}',[VerficationController::class,'Check_verfication']);
Route::post('customer_verfication',               [VerficationController::class,'Customer_verfication']);
// Route::get('sr_validation/{serialnumber}/{expiring}',[VerficationController::class,'sr_validation']);
Route::post('sr_validation_date',                 [VerficationController::class,'sr_validation_date']);


// Register

Route::post('registers',                          [AuthController::class, 'register']);
Route::post('logins',                             [AuthController::class, 'login']);
Route::get('getlogin',    [AuthController::class, 'getlogin']);
Route::get('gettoken',                            [AuthController::class, 'gettoken']);
Route::get('customerlogin/{tenantcode}/{token}',  [AuthController::class, 'getcustomerlogin']);
Route::get('getcustid/{id}',                      [AuthController::class, 'getid']);
 Route::post('logouts',                           [AuthController::class, 'logout']);

Route::get('getlogin/{tenantcode}/{password}',    [AuthController::class, 'getlogin']);
Route::get('gettoken',                            [AuthController::class, 'gettoken']);
Route::get('customerlogin/{tenantcode}/{token}',  [AuthController::class, 'getcustomerlogin']);


// Users
Route::resource('users',                                    UsersController::class);
Route::get('gettenant/{tenantcode}',                       [UsersController::class, 'gettenant']);
Route::get('usersdeactivelist',                            [UsersController::class, 'deactiveuserslist']);
Route::put('users/{id}/{active}/status',                   [UsersController::class, 'userstatus']);
Route::get('usersactive',                                  [UsersController::class, 'activeuser']);
Route::get('usersdeactive',                                [UsersController::class, 'deactiveuser']);
Route::get('customers',                                    [UsersController::class, 'getcustomer']);
Route::get('customerlogin/{tenantcode}/{password}/{token}',[UsersController::class, 'customerlogin']);

//Role
Route::resource('roles',                                    RoleController::class);
Route::get('rolesgetexcept',                               [RoleController::class, 'rolesgetexcept']);
Route::get('rolesdeactivelist',                            [RoleController::class, 'deactiverolesslist']);
Route::put('roles/{id}/{active}',                          [RoleController::class, 'rolestatus']);
Route::get('rolesactive',                                  [RoleController::class, 'activerole']);
Route::get('rolesdeactive',                                [RoleController::class, 'deactiverole']);

 //Permission
 Route::resource('permissions',                             PermissionController::class);
 Route::get('permissiondeactivelist',                      [PermissionController::class, 'deactivepermissionslist']);
 Route::put('permissions/{id}/{active}/status',            [PermissionController::class, 'permissionstatus']);
 Route::get('permissionsactive',                           [PermissionController::class, 'activepermission']);
 Route::get('permissionsdeactive',                         [PermissionController::class, 'deactivepermission']);

//  Company
Route::get('customer_wise_company/{id}',                   [CompanyController::class,'customer_wise_company']);

Route::resource('company',                                  CompanyController::class);
// get company By Id
Route::get('getcompanyID/{id}',                            [CompanyController::class, 'getcompanyID']);

// OCFAPI Routes
Route::post('customerdata',                                [OCFAPIController::class, 'customercreate'])->middleware('auth:sanctum');
Route::post('company',                                     [OCFAPIController::class, 'company'])->middleware('auth:sanctum');
Route::post('ocfs',                                        [OCFAPIController::class, 'OCF'])->middleware('auth:sanctum');
Route::get('companydata/{customerid}',                     [OCFAPIController::class, 'getcompany'])->middleware('auth:sanctum');
Route::get('companyocf',                                   [OCFAPIController::class, 'companyocf'])->middleware('auth:sanctum');
Route::post('serialnootp',                                 [OCFAPIController::class, 'serialnootp'])->middleware('auth:sanctum');
Route::post('serialnootpverify',                           [OCFAPIController::class, 'serialnoverifyotp'])->middleware('auth:sanctum');
Route::post('serialno_validity',                           [OCFAPIController::class, 'sr_validity'])->middleware('auth:sanctum');
Route::post('broadcastmessage',                            [OCFAPIController::class, 'broadcastmessage'])->middleware('auth:sanctum');
Route::get('date_time',                                    [OCFAPIController::class, 'date_time'])->middleware('auth:sanctum');
Route::post('pincode',                                     [OCFAPIController::class, 'pincode'])->middleware('auth:sanctum');
Route::post('autologin',                                   [OCFAPIController::class, 'autologin'])->middleware('auth:sanctum');
Route::post('broadcast_messages',                          [OCFAPIController::class, 'broadcast_messages'])->middleware('auth:sanctum');

