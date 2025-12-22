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
use App\Models\InvestmentManagement\Investment;
use App\Models\InvestmentManagement\AccountInvestment;

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

    public function updateTransactionStatus($accountTransaction, $status, $user)
    {

        try{

            DB::beginTransaction();

                if($status === 'approved'){

                    $accountTransaction->update([

                        'status' => $status,
                        'approved_by' => $user->id
                    ]);

                    $transactionType = TransactionType::where('id', $accountTransaction->transaction_type_id)->first();

                    if($transactionType === null){

                        Log::error('Invalid Trasanction type in transaction');

                        DB::rollBack();

                        return false;
                    }

                    if($transactionType->booking === 'credit'){

                        $creditAccount  =  $this->creditAccount($accountTransaction, $transactionType);

                        if(!$creditAccount){

                            Log::error('Error crediting account');

                            DB::rollBack();

                            return false;
                        }

                    }
                    else if($transactionType->booking === 'debit'){

                        $debitAccount  =  $this->debitAccount($accountTransaction, $transactionType);

                        if(!$debitAccount){

                            Log::error('Error debiting account');

                            DB::rollBack();

                            return false;
                        }
                    }
                }
                else if($status === 'rejected'){
            
                    $accountTransaction->update([

                        'status' => $status,
                        'rejected_by' => $user->id
                    ]);
                }
                else if($status === 'reviewed'){
            
                    $accountTransaction->update([

                        'status' => $status,
                        'reviewed_by' => $user->id
                    ]);
                }
                else{

                    Log::error('Invalid Trasanction status provided');
                    return false;
                }

            DB::commit();

            return $accountTransaction;
        }
        catch (\Throwable $e) {
                
            Log::error('Error updating transaction:'. $e->getMessage());

            DB::rollBack();

            return false;
        }
    }


    public function creditAccount($accountTransaction, $transactionType)
    {
        try{

            DB::beginTransaction();

                $account =  Account::where('id', $accountTransaction->account_id)->first();

                if($account === null){

                    Log::error('Account not found when updating transaction');

                    return false;
                }

                /*
                    Check if there are any open investments
                    Need to record these so as to track this
                */

                $openInvestments = Investment::where('status', 'open')->pluck('id');
                
                if(!empty($openInvestments)){

                    $accountLegder = AccountInvestment::whereIn('investment_id', $openInvestments)
                    ->where('account_id', $account->id)->get();

                    foreach($accountLegder as $ledger){

                        
                        $ledger->update([

                            'investment_changed' => true,
                            'amount_deposited' => ($ledger->amount_deposited + $accountTransaction->amount),
         
                        ]);

                        $ledger->transactions()->create([

                            'account_transaction_id' => $accountTransaction->id,
                            'account_id' => $account->id,
                            'investment_id' => $ledger->investment_id,
                            'transaction_type_id' => $transactionType->id,
                            'date_of_transaction' => $accountTransaction->date_of_transaction,
                            'amount' => $accountTransaction->amount,

                        ]);
                    }

                }

                if($transactionType->transaction_type === 'Deposit'){

                    $account->update([
                        
                        'total_deposit' => $account->total_deposit + $accountTransaction->amount,
                        'balance' => $account->balance + $accountTransaction->amount,
                    ]);
                }

            DB::commit();

            return $account;
        }
        catch (\Throwable $e) {
                
            Log::error('Error crediting account:'. $e->getMessage());

            DB::rollBack();

            return false;
        }
    }


    public function debitAccount($accountTransaction, $transactionType)
    {
        try{

            DB::beginTransaction();

                $account =  Account::where('id', $accountTransaction->account_id)->first();

                if($account === null){

                    Log::error('Account not found when updating transaction');

                    return false;
                }

                                /*
                    Check if there are any open investments
                    Need to record these so as to track this
                */

                $openInvestments = Investment::where('status', 'open')->pluck('id');
                
                if(!empty($openInvestments)){

                    $accountLegder = AccountInvestment::whereIn('investment_id', $openInvestments)
                    ->where('account_id', $account->id)->get();

                    foreach($accountLegder as $ledger){

                        $ledger->update([

                            'investment_changed' => true,
                            'amount_withdrawn' => ($ledger->amount_withdrawn + $accountTransaction->amount),
                            'amount_invested' => ($ledger->amount_invested - $accountTransaction->amount),
                        ]);

                        $ledger->transactions()->create([

                            'account_transaction_id' => $accountTransaction->id,
                            'account_id' => $account->id,
                            'investment_id' => $ledger->investment_id,
                            'transaction_type_id' => $transactionType->id,
                            'date_of_transaction' => $accountTransaction->date_of_transaction,
                            'amount' => $accountTransaction->amount,

                        ]);
                    }

                }

                if($transactionType->transaction_type === 'Withdrawal'){

                    $account->update([
                        
                        'total_deposit' => $account->total_deposit - $accountTransaction->amount,
                        'balance' => $account->balance - $accountTransaction->amount,
                    ]);
                }

            DB::commit();

            return $account;
        }
        catch (\Throwable $e) {
                
            Log::error('Error debiting account:'. $e->getMessage());

            DB::rollBack();

            return false;
        }
    }

    public function storeWithdrawal($depositVars, $account, $user)
    {
        try{

            DB::beginTransaction();

                $transactionType = TransactionType::where('transaction_type', 'Withdrawal')->first();

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
                
            Log::error('Error storing wisthdrawal:'. $e->getMessage());

            DB::rollBack();

            return false;
        }

    }

}