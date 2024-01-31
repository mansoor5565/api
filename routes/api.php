<?php

use App\Http\Controllers\PostController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::post('post/store',[PostController::class,'store']);
// Route::get('post/index',[PostController::class,'index']);
// Route::delete('post/{id}',[PostController::class,'destroy']);
// Route::put('post/{id}',[PostController::class,'update']);
Route::apiResource('post', PostController::class)->only([
    'index','store','update','destroy'
]);