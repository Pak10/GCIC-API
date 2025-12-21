<?php

namespace App\Jobs\Investments;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Administration\Account;
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

    public function __construct($investment, $discretionaryAccounts, $user)
    {
        $this->investment =  $investment;

        $this->discretionaryAccounts = $discretionaryAccounts;

        $this->user  = $user;
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


                        $accountTransaction =  AccountTransaction::create([

                            'account_id' => $discretionaryAccount->id,
                            'amount' => $accountInvestment->interest_gained,
                            'transaction_type_id' => $investmentTransactionType->id,
                            'transaction_reference'  => Str::uuid(),
                            'date_of_transaction' => Carbon::now(),
                            'approved_by' => $this->user->id,
                            'reviewed_by' => $this->user->id,
                            'status' => 'approved',

                        ]);

                        $account->update([

                            'total_deposit' => ($account->total_deposit + $accountInvestment->interest_gained),
                            'interest_gained' => ($account->interest_gained + $accountInvestment->interest_gained)
                        ]);
                    }


                    /*
                        Handle the rest of the other accounts
                    */

                    $investment = $this->investment;

                    AccountInvestment::with([
                        'account'=>[
                        'type',
                        'plan']
                    ])
                    ->where('investment_id', $investment->id)
                    ->chunkById(50, function ($accountInvestments) use ($investment)  {
                    
                        $accountInvestments->each(function ($accountInvestment, $key) use($investment) {

                            /*
                                We are going through each account to award profit based on their investment plan  

                            */

                            //Check if the account in case investment plan uses share profit
                            if($accountInvestment->account->plan->share_profit == true){

                                $interestGained = ($accountInvestment->amount_invested * $investment->share_profit);
                                $amountReturned = ($accountInvestment->amount_invested + $interestGained);

                                $accountInvestment->update([

                                    'amount_returned' => $amountReturned,
                                    'interest_gained' => $interestGained
                                ]);
                            }
                            else if($accountInvestment->account->plan->has_fixed_interest == true){

                                $interestGained = ($accountInvestment->amount_invested * $accountInvestment->account->plan->fixed_interest);
                                $amountReturned = ($accountInvestment->amount_invested + $interestGained);

                                $accountInvestment->update([

                                    'amount_returned' => $amountReturned,
                                    'interest_gained' => $interestGained
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
