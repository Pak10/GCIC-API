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
use App\Http\Resources\Transactions\TransactionTypeResource;

use App\Http\Requests\Api\Transactions\RecordDepositRequest;
use App\Http\Requests\Api\Transactions\UpdateTransactionStatusRequest;

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




    public function getTransactions(Request $request)
    {

        $user =  Auth::user();

        if($user->can('view-transactions')){

            $accountTransactions = AccountTransaction::orderBy('created_at', 'desc');
        }
        else{


        }

        if(!empty($request->transaction_type_id)){

            $transactionTypeId = $request->transaction_type_id;

            $accountTransactions = $accountTransactions->where('transaction_type_id', $transactionTypeId);

        }

        $pageSize = 10;

        if(!empty($request->page_size)){

            $pageSize = $request->page_size;
        }

        $accountTransactions = $accountTransactions->paginate($pageSize);

        return AccountTransactionResource::collection($accountTransactions);
       
    }


    public function getTransactionTypes(Request $request)
    {
        $user =  Auth::user();

        if(!($user->can('view-transaction-types'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }

        $transactionTypes = TransactionType::orderBy('created_at', 'desc')->get();

        return TransactionTypeResource::collection($transactionTypes);

    }

    public function updateTransactionStatus(UpdateTransactionStatusRequest $request, $transactionReference)
    {
        $user =  Auth::user();

        $validated = $request->validated();

        $accountTransaction = AccountTransaction::where('transaction_reference', $transactionReference)->first();

        if($accountTransaction === null){

            return response()->json([

                'message' => 'Invalid transaction provided'
            ],400);
        }

        /*
            In this flow, there are specific conditions we need to validate depending on the status
            the user wishes to update to 
        */

        /*
            If the required action is to approve the transaction
            1. The user should have permission to approve transactions
            2. The current transaction status should be pending
        */

        if($validated['status'] == 'approved'){

            if(!($user->can('approve-transaction'))){

                return response()->json([
    
                    'message' => 'User does not have access to this resource'
                ],403);
            }

            if($accountTransaction->status !== 'pending'){

                return response()->json([
    
                    'message' => 'Invalid Transaction status'
                ],400);

            }
        }

        /*
            If the required action is to reject the transaction
            1. The user should have permission to reject transactions
            2. The current transaction status should be pending
        */

        if($validated['status'] == 'rejected'){

            if(!($user->can('reject-transaction'))){

                return response()->json([
    
                    'message' => 'User does not have access to this resource'
                ],403);
            }

            if($accountTransaction->status !== 'pending'){

                return response()->json([
    
                    'message' => 'Invalid Transaction status'
                ],400);

            }
        }

        $accountTransaction = $this->transactionService->updateTransactionStatus($accountTransaction, $validated['status'], $user);

        if($accountTransaction){

            return new AccountTransactionResource($accountTransaction);

        }
        else{

            return response()->json([

                'message' => 'Error updating the transaction'
            ],500);
        }

    }

}
