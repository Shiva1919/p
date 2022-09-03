<?php

use App\Http\Controllers\API\CustomersController;
use App\Http\Controllers\API\OrderConfirmationsController;
use App\Http\Controllers\API\PackagesController;
use App\Http\Controllers\API\SerialnoController;
use App\Http\Controllers\API\SerialnosController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Package
Route::resource('package', PackagesController::class);

// SubPackage
Route::get('subpackage/{packageid}', [PackagesController::class, 'subpackageindex']);
Route::get('subpackagegetbyid/{packageid}/{id}', [PackagesController::class, 'subpackageshow']);
Route::post('subpackageadd/{packageid}', [PackagesController::class, 'subpackagestore']);
Route::put('subpackageupdate/{packageid}/{id}', [PackagesController::class, 'subpackageupdate']);
Route::delete('subpackagedelete/{packageid}/{id}', [PackagesController::class, 'subpackagedelete']);

// Module
Route::get('module/{id}', [PackagesController::class, 'moduleindex']);
Route::get('modulegetbyid/{packageid}/{id}', [PackagesController::class, 'moduleshow']);
Route::post('moduleadd/{packageid}', [PackagesController::class, 'modulestore']);
Route::put('moduleupdate/{packageid}/{id}', [PackagesController::class, 'moduleupdate']);
Route::delete('moduledelete/{packageid}/{id}', [PackagesController::class, 'moduledelete']);

// Customer
Route::resource('customer', CustomersController::class);

//Country
Route::get('state', [CustomersController::class, 'getState']);
Route::get('district/{stateid}', [CustomersController::class, 'getDistrict']);
Route::get('taluka/{districtid}', [CustomersController::class, 'getTaluka']);
Route::get('city/{talukaid}', [CustomersController::class, 'getCity']);

// Branch
Route::get('branch/{customerid}', [CustomersController::class, 'branchindex']);
Route::get('branchgetbyid/{customerid}/{id}', [CustomersController::class, 'branchshow']);
Route::post('branchadd/{customerid}', [CustomersController::class, 'branchstore']);
Route::put('branchupdate/{customerid}/{id}', [CustomersController::class, 'branchupdate']);
Route::delete('branchdelete/{customerid}/{id}', [CustomersController::class, 'branchdelete']);

// Contact
Route::get('contact/{customerid}', [CustomersController::class, 'contactindex']);
Route::get('contactgetbyid/{customerid}/{id}', [CustomersController::class, 'contactshow']);
Route::post('contactadd/{customerid}', [CustomersController::class, 'contactstore']);
Route::put('contactupdate/{customerid}/{id}', [CustomersController::class, 'contactupdate']);
Route::delete('contactdelete/{customerid}/{id}', [CustomersController::class, 'contactdelete']);

// Order Confirmation Form
Route::resource('orderconfirmation', OrderConfirmationsController::class);
Route::delete('orderconfirmationdelete/{id}', [OrderConfirmationsController::class, 'destroy']);

// Serialno
Route::resource('serialno', SerialnoController::class);
Route::post('serialnosendotp/{ocfno}', [SerialnoController::class, 'serialnosendotp']);
Route::put('serialnoverifyotp/{mobile}', [SerialnoController::class, 'serialnoverifyotp']);
Route::get('serialnos/{ocfno}', [SerialnoController::class, 'serialnogenerate']);
Route::put('ocfchange/{ocfno}', [SerialnoController::class, 'ocfchange']);

