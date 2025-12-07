<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AccountController;

Route::post('/auth-verification', [AuthController::class, 'verifyAuthOtp'])->name('verify.auth.otp');

/////////////////////////AUTHENTICATED / PROTECTED  ROUTES //////////////////////////////
Route::middleware(['auth:sanctum', 'is_account_approved'])->group(function () {

    ////////////////////ACCOUNT MANAGEMENT ROUTES ///////////////////////////////////////

    Route::get('/profile', [AuthController::class, 'getProfile'])->name('profile');
    //////////////////////////////////////////////////////////////////////////////////////

    ///////////////////Register Member ///////////////////////////////////////////////////

    Route::post('/members', [MemberController::class, 'registerMember'])->name('register.members');
    Route::get('/members', [MemberController::class, 'getMembers'])->name('fetch.members');


});
