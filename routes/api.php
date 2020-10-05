<?php

use App\Http\Controllers\TodoController;
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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post("/todos/completed", [    TodoController::class, "completed"]);
Route::post("/todos/restore/{id}", [ TodoController::class, "restore"]);
Route::post("/todos/store", [        TodoController::class, "store"]);
Route::post("/todos/update/{id}", [  TodoController::class, "update"]);
Route::post("/todos/delete/{id}", [  TodoController::class, "destroy"]);
Route::post("/todos/complete/{id}", [TodoController::class, "complete"]);
