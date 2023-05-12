<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\AuthController;

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

//Protected Route
Route::group (['middleware' => ['auth:sanctum', 'App\Http\Middleware\AdminAuth']], function () {
    Route::resource('/teams', TeamController::class); //team route
    Route::resource('/players', PlayerController::class); //player route
    Route::get('test-safe', [TeamController::class, 'testSafe']);
});

//Public Route
Route::get('/teams', [TeamController::class, 'index']); //team route
Route::get('/teams/{id}', [TeamController::class, 'show']); //team route

Route::get('/players', [PlayerController::class, 'index']); //player route
Route::get('/players/{id}', [PlayerController::class, 'show']); //player route

Route::post('/login', [AuthController::class, 'login']);

Route::get('unauth-response', [TeamController::class, 'unauthResponse'])->name('login');
