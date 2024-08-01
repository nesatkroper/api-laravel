<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [\App\Http\Controllers\Api\UserController::class, 'createUser']);
Route::post('/login', [\App\Http\Controllers\Api\UserController::class, 'loginUser']);

Route::prefix('v1')->group(function () {
  Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('simple', [\App\Http\Controllers\Api\UserController::class, 'simple']);
  });
});
// Route::group(['middleware' => ['auth:sanctum'], function () {
//     Route::post('/hello', [\App\Http\Controllers\Api\UserController::class, 'hello']);
// }]);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');