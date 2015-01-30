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
	return View::make('index');
});

Route::group(['prefix' => 'api/v1.0', 'before' => 'auth.api'], function()
{
	Route::resource('markers', 'MarkersController');
	Route::get('photos/no-marker', 'PhotosController@noMarker');
	Route::resource('photos', 'PhotosController');
	Route::post('photos/{id}', 'PhotosController@partialUpdate');
	Route::resource('files', 'FilesController', ['only' => 'show']);
});

Route::post('login', function()
{
	$email = Input::get('email', 'bogus');
	$password = Input::get('password', 'bogus');

	$apiController = App::make('ApiController');

	if (Auth::attempt(['email' => $email, 'password' => $password])) {

		return $apiController->successResponse(Auth::user()->toArray(), 'Login successful!');
	}

	return $apiController->errorResponse(['login' => 'The email or password that you entered is invalid.'], 'Invalid login', 'error', 401);
});

Route::get('logout', function()
{
	if (!Auth::guest())
	{
		Auth::logout();
	}
});
