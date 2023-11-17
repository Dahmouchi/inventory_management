<?php

use App\Http\Controllers\api\auth\AdminAuthController;
use App\Http\Controllers\api\PartsController;
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








Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/', [AdminAuthController::class, "index"]);

    Route::prefix('auth')->group(function () {
        Route::delete('logout', [AdminAuthController::class, "logout"]);
    });

    Route::apiResource('parts', PartsController::class);

});

//Auth Routes

Route::prefix('auth')->group(function () {

    Route::post('login', [AdminAuthController::class, "login"]);
    Route::post('register', [ AdminAuthController::class, "register"]);


});
