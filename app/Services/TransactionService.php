<?php

namespace App\Services;

use Illuminate\Support\Str;
use Log;
use Exception;
use DB;
use Carbon\Carbon;
use App\Models\Administration\Account;
use App\Models\Transactions\TransactionType;
use App\Models\Transactions\AccountTransaction;

class TransactionService {


    public function storeDeposit($depositVars, $account, $user)
    {

        try{

            DB::beginTransaction();

                $transactionType = TransactionType::where('transaction_type', 'Deposit')->first();

                if($transactionType === null){

                    Log::error('Invalid Transaction Type');

                    return false;

                }

                $depositVars['account_id'] = $account->id;
                $depositVars['transaction_type_id'] = $transactionType->id;
                $depositVars['data'] = $depositVars;

                $accountTransaction = AccountTransaction::create($depositVars);

            DB::commit();

            return $accountTransaction;
        }
        catch (\Throwable $e) {
                
            Log::error('Error storing deposit:'. $e->getMessage());

            DB::rollBack();

            return false;
        }




    }


}