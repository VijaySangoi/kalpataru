<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
	return redirect('/login');
    // return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function(){
	Route::get('/home', 'App\Http\Controllers\Web\HomeController@index')->name('home');
	Route::get('/job', 'App\Http\Controllers\Web\JobController@index');
	Route::get('/workers', 'App\Http\Controllers\Web\WorkerController@index');
	Route::get('/nodes', 'App\Http\Controllers\Web\NodesController@index');
	Route::get('/logs', 'App\Http\Controllers\Web\LogController@index');
	Route::get('/triggers', 'App\Http\Controllers\Web\TriggersController@index');
	Route::get('/devices', 'App\Http\Controllers\Web\DevicesController@index');
});

// Route::group(['middleware' => 'auth'], function () {
// 	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
// 	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
// 	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
// 	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
// });

