<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->prefix('users')->group(function () {
    Route::get('', 'index');
    Route::get('{cpf}', 'show');
    Route::post('', 'store');
    Route::put('{cpf}', 'update');
    Route::delete('{cpf}', 'destroy');
});
