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

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/me', [AuthController::class, 'user'])->middleware('auth:api');
});


Route::group(['prefix' => 'todo', 'as' => 'todo.', "middleware" => "auth:api"], function () {
    Route::get('/all', [TodoController::class, 'getAll']);
    Route::post('/add', [TodoController::class, 'addTodo']);
    Route::delete('/delete', [TodoController::class, 'deleteJob']);
    Route::group(['prefix' => 'find', 'as' => 'find.'], function () {
        Route::get('/date', [TodoController::class, 'findWithDate']);
        Route::get('/job', [TodoController::class, 'findJob']);
    });
    Route::group(['prefix' => 'update', 'as' => 'update.'], function () {
        Route::put('/job', [TodoController::class, 'changeJobName']);
        Route::put('/complete', [TodoController::class, 'makeComplete']);
    });
});
