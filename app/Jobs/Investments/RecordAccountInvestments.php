<?php

namespace App\Jobs\Investments;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Administration\Account;
use Log;
use DB;

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

            $investment = $this->investment;

        
            Account::whereHas('plan.options', function($query) use($investment){

                //$query->where('investment_option_id', $investment->investment_option_id);
            })
            ->with(['type', 'plan.options'])
            ->chunkById(50, function ($accounts) {
                
                $accounts->each(function ($account, $key){

                    info($account);
                });

            });

        }
        catch (\Throwable $e) {
                
            Log::error('Error recording account investments:'. $e->getMessage());

            DB::rollBack();

            return;
        }
    }
}
