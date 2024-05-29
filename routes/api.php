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
    Route::get('/trigger/{job_id}','App\Http\Controllers\Api\AllApiController@trigger');
    Route::get('/option','App\Http\Controllers\Api\AllApiController@option');
    Route::post('/job','App\Http\Controllers\Api\AllApiController@job');
    Route::get('/file/{file}','App\Http\Controllers\Api\AllApiController@file');
    Route::match(['GET','POST','DELETE'],'/nodes','App\Http\Controllers\Api\AllApiController@nodes');
    Route::match(['GET','POST','DELETE'],'/devices','App\Http\Controllers\Api\AllApiController@devices');
    Route::post('/dev_pos','App\Http\Controllers\Api\AllApiController@dev_pos');
    Route::match(['GET','POST'],'/log-file','App\Http\Controllers\Api\AllApiController@log_files');
    Route::match(['GET','POST','DELETE'],'/workers','App\Http\Controllers\Api\AllApiController@list_workers');
    Route::match(['GET','POST','DELETE'],'/trigger','App\Http\Controllers\Api\AllApiController@list_trigger');
    Route::post('/dashboard_component','App\Http\Controllers\Api\AllApiController@dashboard_component');
    Route::post('/sheet','App\Http\Controllers\Api\AllApiController@add_sheet');
});
Route::match(['GET','POST'],'/devices/message','App\Http\Controllers\Api\AllApiController@devices_message');