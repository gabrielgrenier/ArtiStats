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

//home
Route::view('/', 'pages.home')->name('home');

//API
Route::get('search/{term}', 'GeniusController@search')->name('search');


/*
Route::get('/', function () {
    return view('welcome');
});
*/
