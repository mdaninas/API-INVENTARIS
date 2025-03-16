<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('users', [UserController::class, 'register']);
Route::post('users/login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('abc', function () {
        return response()->json([
            'message' => 'testing'
        ]);
    });
    Route::post('users/logout', [UserController::class,'logout']);
    Route::post('users/update', [UserController::class,'update']);
    
    Route::get('item/', [ItemController::class,'showRelated']);
    Route::post('item/create', [ItemController::class,'createItem']);

});
