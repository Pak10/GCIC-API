<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\InvestmentController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\TransactionController;

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

        Route::get('/accounts/types', [AccountController::class, 'getAccountTypes'])->name('account.types');

        ///////////////////// INVESTMENT MANAGEMENT ROUTES  /////////////////////////////////////

        Route::get('/investments/options', [InvestmentController::class, 'getInvestmentOptions'])->name('investment.options');
        Route::get('/investments/plans', [InvestmentController::class, 'getInvestmentPlans'])->name('investment.plans');
        Route::post('/investments', [InvestmentController::class, 'recordInvestment'])->name('investment.store');

        ///////////////////// TRANSACTION MANAGEMENT ROUTES  /////////////////////////////////////

        Route::get('/transactions/types', [TransactionController::class, 'getTransactionTypes'])->name('view.transactions.types');
        Route::get('/transactions', [TransactionController::class, 'getTransactions'])->name('view.transactions');
        Route::put('/transactions/{transaction}/status', [TransactionController::class, 'updateTransactionStatus'])->name('update.transaction');
        Route::post('/deposits', [TransactionController::class, 'recordDeposit'])->name('deposit');
        Route::post('/withdrawals', [TransactionController::class, 'recordWithdrawal'])->name('withdrawals');

    });


});
