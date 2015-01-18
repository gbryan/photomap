<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::group(['prefix' => 'api/v1.0', 'before' => 'api_auth'], function()
{
	Route::resource('markers', 'MarkersController');
	Route::resource('photos', 'PhotosController');
	Route::post('photos/{id}', 'PhotosController@partialUpdate');
	Route::resource('files', 'FilesController');
});
