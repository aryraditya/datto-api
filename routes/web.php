<?php

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

Auth::routes(['register' => false]);

Route::group(['middleware' => ['auth']], function() {
    Route::redirect('home', 'devices');

    Route::get('devices', 'DeviceController@all')->name('device.all');
    Route::get('devices/{sn}/assets', 'DeviceController@assets')->name('device.assets');

    Route::get('company/{id}/report/regional', 'CompanyController@reportRegional')->name('company.report.regional');
    Route::get('company/{id}/report/asset', 'CompanyController@reportAsset')->name('company.report.asset');
    Route::get('company/{id}/report/storage', 'CompanyController@reportStorage')->name('company.report.storage');
    Route::resource('company', 'CompanyController', [
        'except'    => ['show'],
    ]);

    Route::resource('user', 'UserController', [
        'except'    => ['show']
    ]);
});
