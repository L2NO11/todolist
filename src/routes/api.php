<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

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
Route::post('/auth/register',[AuthController::class,'register']);
Route::get('/auth/me',[AuthController::class,'user'])->middleware('auth:api');
Route::get('/todo/all',[TodoController::class,'getAll'])->middleware('auth:api');
Route::get('/todo/add',[TodoController::class,'addTodo'])->middleware('auth:api');
Route::get('/todo/find/date',[TodoController::class,'findWithDate'])->middleware('auth:api');
Route::get('/todo/find/job',[TodoController::class,'findJob'])->middleware('auth:api');
Route::put('/todo/update/job',[TodoController::class,'changeJobName'])->middleware('auth:api');
Route::put('/todo/update/complete',[TodoController::class,'makeComplete'])->middleware('auth:api');
Route::delete('/todo/delete',[TodoController::class,'deleteJob'])->middleware('auth:api');
