<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix'=>'students'], function (){

    Route::get('/','StudentController@index');

    Route::get('/{student}','StudentController@show');

    Route::delete('/{student}','StudentController@destroy');

    Route::put('/','StudentController@store');

});

Route::group(['prefix'=>'courses'], function (){

    Route::get('/','CourseController@index');

    Route::get('/{course}','CourseController@show');

    Route::put('/','CourseController@store');

});