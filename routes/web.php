<?php

use App\Http\Controllers\API\JSONStoreController;
use App\Http\Controllers\API\SerialnoController;
use App\Http\Controllers\API\SerialnosController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ModulesController;
use App\Http\Controllers\OrderConfirmationController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubPackageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
    return view('welcome');
 });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::get('upgrade', function () {return view('pages.upgrade');})->name('upgrade'); 
	 Route::get('map', function () {return view('pages.maps');})->name('map');
	 Route::get('icons', function () {return view('pages.icons');})->name('icons'); 
	 Route::get('table-list', function () {return view('pages.tables');})->name('table');
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});

//package
Route::resource('/package',PackageController::class);

//subpackage
Route::resource('/subpackage',SubPackageController::class,['parameters' => 
															[
																'index' => 'owncode',
																'create' => 'packagetype',
																'edit' => 'owncode','packagetype',
																'update' => 'owncode','packagetype'
															]]);

//module
Route::resource('/module',ModuleController::class,['parameters' => 
													[
														'index' => 'owncode',
														'create' => 'ProductType',
														'edit' => 'owncode','ProductType',
														'update' => 'owncode','ProductType'
													]]);


//customer
Route::resource('/customer',CustomerController::class);
Route::post('getDistrict', [CustomerController::class, 'getDistrict']);
Route::post('getTaluka', [CustomerController::class, 'getTaluka']);
Route::post('getCity', [CustomerController::class, 'getCity']);

//branch
Route::resource('branch', BranchController::class, ['parameters' => 
													[
														'index' => 'owncode',
														'create' => 'customercode',
														'edit' => 'owncode','customercode',
														'update' => 'owncode','customercode'
													]]);

Route::post('getDistricts', [BranchController::class, 'getDistrict']);
Route::post('getTalukas', [BranchController::class, 'getTaluka']);
Route::post('getCitys', [BranchController::class, 'getCity']);

//contact
Route::resource('/contact', ContactController::class, ['parameters' => 
													[
														'index' => 'owncode',
														'create' => 'customercode',
														'edit' => 'owncode','customercode',
														'update' => 'owncode','customercode'
													]]);

//Order Confirmation form
Route::resource('order_confirmation',OrderConfirmationController::class);
Route::post('getCustomer', [OrderConfirmationController::class, 'getCustomer']);
Route::post('getStates', [OrderConfirmationController::class, 'getStates']);
Route::post('getCitys', [OrderConfirmationController::class, 'getCitys']);
Route::post('getBranch', [OrderConfirmationController::class, 'getBranch']);
Route::post('getSubPackage', [OrderConfirmationController::class, 'getSubPackage']);


Route::resource('serialnos', SerialnoController::class, ['parameters' => 
													[
														'store' => 'ocfno',
														
													]]);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
