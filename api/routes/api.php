<?php

use App\Http\Controllers\AccessTokensController;
use App\Http\Controllers\QuoteController;
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
Route::prefix('quote')->name('quote.')->middleware(AccessTokenAuthentication::class)->group(function(){
    Route::get('/', [QuoteController::class, 'index'])->name('get');
    /**
     * Couldn't settle on whether DESTROY fitted here.  
     * I've gone for refresh as it affords a little more readability I feel.
     */
    Route::get('/refresh', [QuoteController::class, 'refresh'])->name('refresh');
});


//open routes
Route::post('token', [AccessTokensController::class, 'token'])->name('auth.token');