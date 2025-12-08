<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transactions\AccountTransaction;
use App\Models\Transactions\TransactionType;
use App\Models\Administration\Account;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Str;
use DB;

use App\Services\TransactionService;

use App\Http\Resources\Transactions\AccountTransactionResource;

use App\Http\Requests\Api\Transactions\RecordDepositRequest;

class TransactionController extends Controller
{
    
    public function __construct()
    {
        $this->transactionService = new TransactionService;
      
    } 


    public function recordDeposit(RecordDepositRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();

        $account = Account::where('account_identifier', $validated['account_identifier'])->first();

        if($account === null){

            return response()->json([

                'message' => 'Invalid Account provided'
            ],400);

        }

        $deposit = $this->transactionService->storeDeposit($validated, $account, $user);

        if($deposit){

            return new AccountTransactionResource($deposit);

        }
        else{

            return response()->json([

                'message' => 'Error saving the deposit'
            ],500);
        }

    }

}
