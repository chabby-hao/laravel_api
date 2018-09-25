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

//$a = app()->make('files');
////$a = app('files');
////$a = new \Illuminate\Filesystem\Filesystem;
//$b = Illuminate\Support\Facades\File::class;
//$d = new $b;
//var_dump($a, $b, $d);
//
//echo 333;exit;

Route::get('/', 'Admin\AdminController@login');

//Route::get('/', function () {
//    return view('welcome');
//});
