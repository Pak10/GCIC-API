<?php

namespace App\Jobs\Investments;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Administration\Account;
use App\Models\Administration\Setting;
use App\Models\InvestmentManagement\AccountInvestment;
use App\Models\Transactions\AccountTransaction;
use App\Models\Transactions\TransactionType;
use Log;
use DB;
use Str;
use Carbon\Carbon;

class SettleInvestment implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $investment;

    protected $discretionaryAccounts;

    protected $user;

    protected $generalInterest;

    public function __construct($investment, $discretionaryAccounts, $user, $generalInterest)
    {
        $this->investment =  $investment;

        $this->discretionaryAccounts = $discretionaryAccounts;

        $this->user  = $user;

        $this->generalInterest = $generalInterest;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /*
            Function to handle settlement of the accounts
        */
        
        try{

            DB::beginTransaction();

                $investmentTransactionType =  TransactionType::where('transaction_type', 'Investment Interest')->first();

                if($investmentTransactionType === null){

                    Log::error('Invalid Transaction Type:');

                    DB::rollBack();
        
                    return;
                }

                $settings =  Setting::orderBy('created_at', 'asc')->first();

                
                /*
                    Begin with discretionary accounts
                */
                foreach($this->discretionaryAccounts as $account){

                    $discretionaryAccount = Account::where('account_identifier', $account['account_identifier'])->first();

                    if($discretionaryAccount === null){

                        Log::error('Invalid account identifier provided:'. $e->getMessage());

                        DB::rollBack();
            
                        return;

                    }

                    $accountInvestment =  AccountInvestment::where('account_id', $discretionaryAccount->id)
                    ->where('investment_id', $this->investment->id)->first();

                    if($accountInvestment !== null){

                        $accountInvestment->update([

                            'amount_returned' => $account['amount_returned'],
                            'interest_gained' => ($account['amount_returned'] - $accountInvestment->amount_invested),
                        ]);

                        $transactionData = [

                            'amount' => $accountInvestment->interest_gained
                        ];

                        $accountTransaction =  AccountTransaction::create([

                            'account_id' => $discretionaryAccount->id,
                            'amount' => $accountInvestment->interest_gained,
                            'transaction_type_id' => $investmentTransactionType->id,
                            'transaction_reference'  => Str::uuid(),
                            'data' => $transactionData,
                            'date_of_transaction' => Carbon::now(),
                            'approved_by' => $this->user->id,
                            'reviewed_by' => $this->user->id,
                            'status' => 'approved',

                        ]);

                        $discretionaryAccount->update([

                            'balance' => ($discretionaryAccount->balance + $accountInvestment->interest_gained),
                            'interest_gained' => ($discretionaryAccount->interest_gained + $accountInvestment->interest_gained)
                        ]);

                    }

                    /*
                        Handle the rest of the other accounts
                    */

                    $investment = $this->investment;

                    $generalInterest = $this->generalInterest;

                    AccountInvestment::with([
                        'account'=> [
                        'type',
                        'plan']
                    ])
                    ->where('investment_id', $investment->id)
                    ->chunkById(50, function ($accountInvestments) use ($investment, $generalInterest)  {
                    
                        $accountInvestments->each(function ($accountInvestment, $key) use($investment, $generalInterest) {

                            /*
                                We are going through each account to award profit based on their investment plan  

                            */

                            //Check if the account in case investment plan uses share profit
                            if($accountInvestment->account->plan->share_profit == true){

                                $interestGained = ($accountInvestment->amount_invested * ($investment->share_profit/100));

                                $gcicDeduction = null;

                                if($accountInvestment->direct_investment == true){

                                    $gcicDeduction = ($interestGained * $settings->gcic_investment_deduction);
                                    $interestGained = ($interestGained - $gcicDeduction);

                                }
                                
                                $amountReturned = ($accountInvestment->amount_invested + $interestGained);

                                $accountInvestment->update([

                                    'amount_returned' => $amountReturned,
                                    'interest_gained' => $interestGained,
                                    'gcic_deduction' => $gcicDeduction,
                                ]);
                            }
                            else if($accountInvestment->account->plan->has_fixed_interest == true){


                                $interestGained = ($accountInvestment->amount_invested * ($accountInvestment->account->plan->fixed_interest/100));

                                $gcicDeduction = null;

                                if($accountInvestment->direct_investment == true){

                                    $gcicDeduction = ($interestGained * $settings->gcic_investment_deduction);
                                    $interestGained = ($interestGained - $gcicDeduction);

                                }

                                $amountReturned = ($accountInvestment->amount_invested + $interestGained);

                                $accountInvestment->update([

                                    'amount_returned' => $amountReturned,
                                    'interest_gained' => $interestGained,
                                    'gcic_deduction' => $gcicDeduction,
                                ]);
                            }
                            else{

                                $interestGained = ($accountInvestment->amount_invested * $generalInterest);

                                $gcicDeduction = null;

                                if($accountInvestment->direct_investment == true){

                                    $gcicDeduction = ($interestGained * $settings->gcic_investment_deduction);
                                    $interestGained = ($interestGained - $gcicDeduction);

                                }

                                $amountReturned = ($accountInvestment->amount_invested + $interestGained);

                                $accountInvestment->update([

                                    'amount_returned' => $amountReturned,
                                    'interest_gained' => $interestGained,
                                    'gcic_deduction' => $gcicDeduction,
                                ]);
                            }
                        });
                    });
                }

            DB::commit();
        }
        catch (\Throwable $e) {
                
            Log::error('Error settling account investments:'. $e->getMessage());

            DB::rollBack();

            return;
        }


    }
}
