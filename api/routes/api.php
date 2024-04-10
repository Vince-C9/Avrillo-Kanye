<?php

use App\Http\Controllers\AccessTokensController;
use App\Http\Middleware\AccessTokenAuthentication;
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

Route::middleware(AccessTokenAuthentication::class)->get('/health-check', function (Request $request) {
    echo 'Site active';
})->name("health-check");


//Auth routes
Route::prefix('auth')->name('auth.')->middleware(AccessTokenAuthentication::class)->group(function(){
    
});


//open routes
Route::post('token', [AccessTokensController::class, 'token'])->name('auth.token');