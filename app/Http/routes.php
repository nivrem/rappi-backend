<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('/', ["as" => "update", 'uses' => 'Matrix@updateMatrix']);
Route::post('fetch', ["as" => "fetch", 'uses' => 'Matrix@fetchMatrix']);
Route::post('createMatrix', ["as" => "createMatrix", 'uses' => 'Matrix@createMatrix']);
