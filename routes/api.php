<?php

use Illuminate\Support\Facades\Route;

Route::group([
	'namespace' => 'App\Http\Controllers\Api',
], function () {
	Route::group([
		'prefix' => 'v1',
		'namespace' => 'V1',
	], function () {
		Route::group([
			'prefix' => 'auth',
			'namespace' => 'Authentication',
		], function () {
			Route::post('register', 'RegisterController@register');
			Route::post('login', 'LoginController@authenticate');
			Route::middleware('auth:sanctum')->group(function () {
				Route::get('user', 'LoginController@user');
				Route::post('logout', 'LoginController@logout');
			});
		});

		Route::middleware('auth:sanctum')->group(function () {
			Route::get('onboarding', 'OnboardingController@index');
			Route::post('onboarding', 'OnboardingController@store');
			Route::post('transcribe', 'TranscriptionController@transcribe');
		});
	});
});

# fallback route
Route::fallback(function () {
	return response()->json([
		'message' => 'Not Found',
		'type' => 'error',
		'details' => 'The requested resource was not found.'
	], 404);
});
