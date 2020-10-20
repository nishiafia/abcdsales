<?php

use Illuminate\Support\Facades\Route;

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

Route::post('/newsignup', 'API\UserController@create');
Route::post('/vuelogin', 'Auth\LoginController@vuelogin');
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/getcompany', 'API\UserController@getcompany');
Route::get('/getbranchcontactperson', 'API\UserController@getbranchcontactperson');
Route::get('/getbusinesscategory', 'API\CategoryController@getbusinesscategory');


Route::get('/test', function() {
    return auth::user();
});

Route::get('/', function () {
    //return view('welcome');
    return view('master');
});


//Auth::routes();
