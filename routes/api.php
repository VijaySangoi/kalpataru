<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login','App\Http\Controllers\Api\AuthController@login');
Route::post('/register','App\Http\Controllers\Api\AuthController@register');
Route::middleware('auth:sanctum')->group( function () {
    Route::get('/list-log-file','App\Http\Controllers\Api\AllApiController@list_log_files');
    Route::get('/trigger/{job_id}','App\Http\Controllers\Api\JobController@trigger');
    Route::get('/scrathpad','App\Http\Controllers\Web\ScrathpadController@api');
    Route::match(['GET','POST','DELETE'],'/devices/serial','App\Http\Controllers\Api\AllApiController@list_serial_devices');
    Route::match(['GET','POST','DELETE'],'/log-file','App\Http\Controllers\Api\AllApiController@log_files');
    Route::match(['GET','POST','DELETE'],'/workers','App\Http\Controllers\Api\AllApiController@list_workers');
    Route::match(['GET','POST','DELETE'],'/trigger','App\Http\Controllers\Api\AllApiController@list_trigger');
    Route::post('/cip/register','App\Http\Controllers\Api\AllApiController@cip_register');
    Route::get('/cip','App\Http\Controllers\Api\AllApiController@list_cip');
    Route::match(['GET','POST','DELETE'],'/cip/message','App\Http\Controllers\Api\AllApiController@cip_message');
    Route::match(['GET','POST','DELETE'],'/sensors','App\Http\Controllers\Api\AllApiController@sensors');
    Route::post('/sensor_pos','App\Http\Controllers\Api\AllApiController@sensors_pos');
    Route::post('/dashboard_component','App\Http\Controllers\Api\AllApiController@dashboard_component');
    Route::post('/sheet','App\Http\Controllers\Api\AllApiController@add_sheet');
});