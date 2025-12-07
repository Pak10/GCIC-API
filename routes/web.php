<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


/////////AUTHENTICATION ROUTES FOR SANCTUM //////////////////////////////////

Route::post('/login', [AuthController::class, 'login'])->name('web.login');

Route::post('/logout', [AuthController::class, 'logout'])->name('web.logout');

