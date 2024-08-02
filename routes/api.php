<?php

use App\Http\Controllers\DocController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'doc', 'controller' => DocController::class], function () {
    Route::post('/generate', 'generate');
});

