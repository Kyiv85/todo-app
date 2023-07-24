<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;

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

//User routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

//Authentication
Route::middleware('auth:sanctum')->group(function () {
    //Get All todos
    Route::get('/todos', [TodoController::class, 'index']);

    //Create a ToDo
    Route::post('/todos', [TodoController::class, 'store']);

    //Update ToDo
    Route::put('/todos/{id}', [TodoController::class, 'update']);

    //Delete ToDo
    Route::delete('/todos/{id}', [TodoController::class, 'destroy']);
});