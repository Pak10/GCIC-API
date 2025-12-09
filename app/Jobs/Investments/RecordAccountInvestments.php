<?php

namespace App\Jobs\Investments;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Administration\Account;
use App\Models\Administration\AccountType;
use App\Models\InvestmentManagement\AccountInvestment;
use Log;
use DB;
use Illuminate\Support\Number;
use Str;

class RecordAccountInvestments implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */

    protected $investment;


    public function __construct($investment)
    {
        $this->investment = $investment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        try{

            DB::beginTransaction();

                /////////////////////////////GENERATE THE ACCOUNT LEDGER //////////////////////////////////
                $investment = $this->investment;
                Account::whereHas('plan.options', function($query) use($investment){

                    $query->where('investment_option_id', $investment->investment_option_id);
                })
                ->with(['type', 'plan.options' => function ($query) use($investment) {
                    
                    $query->where('investment_option_id', $investment->investment_option_id);

                }])
                ->chunkById(50, function ($accounts) use($investment) {
                    
                    $accounts->each(function ($account, $key) use($investment){

                        $allocation = $account->plan->options[0]->pivot->allocation;
                        $amountInvested = (($allocation/100) * $account->balance);

                        $investment->accountLedger()->attach($account->id,[

                            'id' => Str::uuid(),
                            'amount_invested' => $amountInvested,
                            'has_fixed_interest' => $account->plan->has_fixed_interest,
                            'fixed_interest' => $account->plan->fixed_interest
                        ]);
                    });
                });

                /////////////////////////////GENERATE THE ACCOUNT TYPE INVESTMENTS  //////////////////////////////////

                $accountTypes =  AccountType::orderBy('created_at', 'desc')->get();

                foreach($accountTypes as $accountType){

                    $accounts =  Account::where('account_type_id', $accountType->id)->pluck('id');

                    $amountInvested = AccountInvestment::whereIn('account_id', $accounts)
                    ->where('investment_id', $investment->id)
                    ->sum('amount_invested');

                    $accountType->investments()->attach($investment->id,[

                        'amount_invested' => $amountInvested
                    ]);
                }

                $investment->update([

                    'status' => 'open'
                ]);

            DB::commit();

        }
        catch (\Throwable $e) {
                
            Log::error('Error recording account investments:'. $e->getMessage());

            DB::rollBack();

            return;
        }
    }
}
