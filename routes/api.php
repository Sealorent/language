<?php

use App\Http\Controllers\api\TranslatorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// get recent with params id
Route::get('/get-recent/{id}', [TranslatorController::class, 'index']);
Route::post('/add-recent', [TranslatorController::class, 'store']);
Route::get('/set-favorite/{id}', [TranslatorController::class, 'setFavorite']);
Route::get('/set-unFavorite/{id}', [TranslatorController::class, 'setUnFavorite']);
