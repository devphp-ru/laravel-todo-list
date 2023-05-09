<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\TodoController;
use App\Http\Controllers\Api\V1\AccessController;
use App\Http\Controllers\Api\V1\FilterController;

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

Route::get('/tags/for-select', [TagController::class, 'forSelect']);
Route::get('/tags/for-filter', [TagController::class, 'forFilter']);
Route::get('/todo/show/{todoList}', [TodoController::class, 'show']);
Route::post('/todo/create', [TodoController::class, 'store']);
Route::post('/todo/edit/{todoList}', [TodoController::class, 'edit']);
Route::delete('/todo/delete/{todoList}', [TodoController::class, 'destroy']);
Route::get('/todo/pagination', [TodoController::class, 'paginateAjax']);
Route::delete('/todo/delete-image/{todoList}', [TodoController::class, 'removeImage']);
Route::get('/access/{id}', [AccessController::class, 'index']);
Route::post('/access/save', [AccessController::class, 'save']);
Route::get('/filter', FilterController::class);
