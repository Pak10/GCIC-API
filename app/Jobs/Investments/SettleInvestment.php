<?php

namespace App\Jobs\Investments;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Administration\Account;
use App\Models\InvestmentManagement\AccountInvestment;
use Log;
use DB;

class SettleInvestment implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $investment;

    protected $discretionaryAccounts;

    public function __construct($investment, $discretionaryAccounts)
    {
        $this->investment =  $investment;

        $this->discretionaryAccounts = $discretionaryAccounts;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /*
            Function to handle settlement of the accounts
        */

        /*
            Begin with discretionary accounts
        */
        
        try{

        
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

                    $account->update([

                        'total_deposit' => ($account->total_deposit + $account['amount_returned']),
                        'interest_gained' => ($account->interest_gained + $accountInvestment->interest_gained)
                    ]);
                }
            }
        }
        catch (\Throwable $e) {
                
            Log::error('Error settling account investments:'. $e->getMessage());

            DB::rollBack();

            return;
        }


    }
}
