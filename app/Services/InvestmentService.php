<?php

namespace App\Services;

use Illuminate\Support\Str;
use Log;
use Exception;
use DB;
use App\Models\Administration\Account;
use App\Models\InvestmentManagement\InvestmentOption;
use App\Models\InvestmentManagement\InvestmentPlan;
use App\Models\InvestmentManagement\Investment;
use App\Jobs\Investments\RecordAccountInvestments;

class InvestmentService {


    public function currentInvestmentTotal()
    {

        $openInvestmentTotal = Investment::where('status', 'open')->sum('amount');

        return $openInvestmentTotal;
        
    }

    public function storeInvestment($investmentVars)
    {
        try{

            DB::beginTransaction();

                $investment = Investment::create($investmentVars);

                $recordAccountInvestments = RecordAccountInvestments::dispatch($investment);

            DB::commit();

            return $investment;

        }
        catch (\Throwable $e) {
                
            Log::error('Error storing investment:'. $e->getMessage());

            DB::rollBack();

            return false;
        }

    }

}