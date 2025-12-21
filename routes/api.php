<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\InvestmentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\AuthorisationController;

Route::post('/auth-verification', [AuthController::class, 'verifyAuthOtp'])->name('verify.auth.otp');

Route::post('/sign-up', [AuthController::class, 'signUp'])->name('sign.up');

/////////////////////////AUTHENTICATED / PROTECTED  ROUTES //////////////////////////////
Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/profile', [AuthController::class, 'getProfile'])->name('profile');

    Route::post('/admin/registration', [MemberController::class, 'completeRegistration'])->name('complete.registration');

});

/////////////////////////AUTHENTICATED / PROTECTED  ROUTES //////////////////////////////
Route::middleware(['auth:sanctum', 'is_account_approved'])->group(function () {

    ////////////////////ACCOUNT MANAGEMENT ROUTES ///////////////////////////////////////

    
    //////////////////////////////////////////////////////////////////////////////////////

    Route::prefix('admin')->group(function () {

        ///////////////////Register Member ///////////////////////////////////////////////////

        Route::post('/members', [MemberController::class, 'registerMember'])->name('register.members');
        Route::get('/members/registrations', [MemberController::class, 'getMemberRegistrations'])->name('member.registrations');
        Route::get('/members/registrations/{registration}', [MemberController::class, 'getMemberRegistration'])->name('member.registration');
        Route::put('/members/registrations/{registration}/status', [MemberController::class, 'updateMemberRegistrationStatus'])->name('update.registration.status');

        Route::get('/members', [MemberController::class, 'getMembers'])->name('fetch.members');
        Route::get('/members/{member}', [MemberController::class, 'getMember'])->name('fetch.member');
        Route::get('/search/members', [MemberController::class, 'searchMembers'])->name('members.search');

        Route::get('/accounts/types', [AccountController::class, 'getAccountTypes'])->name('account.types');
        Route::get('/accounts/discretionary', [AccountController::class, 'getDiscretionaryAccounts'])->name('discretionary.account');

        ///////////////////// INVESTMENT MANAGEMENT ROUTES  /////////////////////////////////////

        Route::get('/investments/options', [InvestmentController::class, 'getInvestmentOptions'])->name('investment.options');
        Route::get('/investments/plans', [InvestmentController::class, 'getInvestmentPlans'])->name('investment.plans');
        Route::post('/investments/plans', [InvestmentController::class, 'createInvestmentPlan'])->name('investment.plans.store');
        Route::post('/investments', [InvestmentController::class, 'recordInvestment'])->name('investment.store');
        Route::get('/investments', [InvestmentController::class, 'getInvestments'])->name('investments');
        Route::get('/investments/{investment}', [InvestmentController::class, 'getInvestment'])->name('investment');
        Route::get('/investments/{investment}/account-types', [InvestmentController::class, 'getAccountTypeInvestment'])->name('account.type.investment');
        Route::put('/investments/{investment}/status', [InvestmentController::class, 'updateInvestmentStatus'])->name('investment.status');
        Route::get('/investments/{investment}/transactions', [InvestmentController::class, 'getInvestmentTransactions'])->name('investment.transactions');
        Route::get('/investments/{investment}/ledger', [InvestmentController::class, 'getInvestmentLedger'])->name('investment.ledger');
        Route::get('/investments/{investment}/transactions/stats', [InvestmentController::class, 'getInvestmentTransactionStats'])->name('investment.transaction.stats');

        ///////////////////// TRANSACTION MANAGEMENT ROUTES  /////////////////////////////////////

        Route::get('/transactions/types', [TransactionController::class, 'getTransactionTypes'])->name('view.transactions.types');
        Route::get('/transactions', [TransactionController::class, 'getTransactions'])->name('view.transactions');
        Route::put('/transactions/{transaction}/status', [TransactionController::class, 'updateTransactionStatus'])->name('update.transaction');
        Route::post('/deposits', [TransactionController::class, 'recordDeposit'])->name('deposit');
        Route::post('/withdrawals', [TransactionController::class, 'recordWithdrawal'])->name('withdrawals');

        //////////////////// USER MANAGEMENT ROUTES ///////////////////////////////////////////////

        Route::get('/users', [UserController::class, 'getUsers'])->name('fetch.users');
        Route::get('/users/{user}', [UserController::class, 'getUser'])->name('fetch.user');
        Route::post('/users', [UserController::class, 'registerUser'])->name('store.user');


        /////////////////// AUTHORISATION ROUTES  ///////////////////////////////////////////////

        Route::get('/roles', [AuthorisationController::class, 'getRoles'])->name('fetch.roles');
        Route::get('/roles/{role}', [AuthorisationController::class, 'getRole'])->name('fetch.role');
        Route::post('/roles', [AuthorisationController::class, 'createRole'])->name('store.role');
        Route::get('/permissions', [AuthorisationController::class, 'getPermissions'])->name('fetch.permissions');
        Route::put('/roles/{role}/permissions', [AuthorisationController::class, 'attachPermissions'])->name('attach.permissions');

    });


});
