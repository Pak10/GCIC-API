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
