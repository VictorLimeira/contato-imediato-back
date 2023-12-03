<?php

use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\MediumController;
use App\Http\Controllers\API\RegisterNewUserController;
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

Route::post('register', [RegisterNewUserController::class, 'register']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('profile', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('contacts', ContactController::class);
    Route::apiResource('contacts.media', MediumController::class);
});
