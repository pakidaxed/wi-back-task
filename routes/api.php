<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/show/{id}', [UserController::class, 'show']);
    Route::post('/create', [UserController::class, 'store']);
    Route::put('/update', [UserController::class, 'update']);
    Route::delete('/delete/{id}', [UserController::class, 'destroy']);
});
