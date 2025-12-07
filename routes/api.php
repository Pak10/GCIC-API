<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\MemberController;

Route::post('/auth-verification', [AuthController::class, 'verifyAuthOtp'])->name('verify.auth.otp');

/////////////////////////AUTHENTICATED / PROTECTED  ROUTES //////////////////////////////
Route::middleware(['auth:sanctum', 'is_account_approved'])->group(function () {

    ////////////////////ACCOUNT MANAGEMENT ROUTES ///////////////////////////////////////

    Route::get('/profile', [AuthController::class, 'getProfile'])->name('profile');
    //////////////////////////////////////////////////////////////////////////////////////

    Route::prefix('admin')->group(function () {

        ///////////////////Register Member ///////////////////////////////////////////////////

        Route::post('/members', [MemberController::class, 'registerMember'])->name('register.members');
        Route::get('/members/registrations', [MemberController::class, 'getMemberRegistrations'])->name('member.registrations');
        Route::put('/members/registrations/{registration}/status', [MemberController::class, 'updateMemberRegistrationStatus'])->name('update.registration.status');

        Route::get('/members', [MemberController::class, 'getMembers'])->name('fetch.members');


    });


});
