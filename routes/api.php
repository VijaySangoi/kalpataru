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
    Route::match(['GET','POST'],'/devices/serial','App\Http\Controllers\Api\AllApiController@list_serial_devices');
    Route::match(['GET','POST'],'/log-file','App\Http\Controllers\Api\AllApiController@log_files');
    Route::match(['GET','POST'],'/workers','App\Http\Controllers\Api\AllApiController@list_workers');
    Route::match(['GET','POST'],'/trigger','App\Http\Controllers\Api\AllApiController@list_trigger');
    Route::post('/cip/register','App\Http\Controllers\Api\AllApiController@cip_register');
    Route::match(['GET','POST'],'/cip/message','App\Http\Controllers\Api\AllApiController@cip_message');
});