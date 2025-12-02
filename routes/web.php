<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthenticationController;


/////////AUTHENTICATION ROUTES FOR SANCTUM //////////////////////////////////

Route::post('/login', [AuthenticationController::class, 'login'])->name('web.login');

Route::post('/logout', [AuthenticationController::class, 'logout'])->name('web.logout');

