<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AccountController;

Route::post('/auth-verification', [AuthController::class, 'verifyAuthOtp'])->name('verify.auth.otp');



/////////////////////////AUTHENTICATED / PROTECTED  ROUTES //////////////////////////////
Route::middleware(['auth:sanctum', 'is_account_approved'])->group(function () {




});
