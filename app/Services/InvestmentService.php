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
use App\Models\InvestmentManagement\AccountInvestment;
use App\Jobs\Investments\RecordAccountInvestments;
use App\Jobs\Investments\SettleInvestment;

class InvestmentService {


    public function currentInvestmentTotal()
    {

        $openInvestmentTotal = Investment::where('status', 'open')
        ->orWhere('status', 'closed')
        ->sum('amount');

        return $openInvestmentTotal;
        
    }


    public function investmentCapital($investment)
    {
        $investmentCapital  =  AccountInvestment::where('investment_id', $investment->id)->sum('amount_invested');

        return $investmentCapital;

    }

    public function storeInvestment($investmentVars)
    {
        try{

            DB::beginTransaction();

                $investment = Investment::create($investmentVars);

                $recordAccountInvestments = SettleInvestment::dispatch($investment);

            DB::commit();

            return $investment;

        }
        catch (\Throwable $e) {
                
            Log::error('Error storing investment:'. $e->getMessage());

            DB::rollBack();

            return false;
        }

    }


    public function updateInvestmentStatus($investment, $investmentVars, $user)
    {
        try{

                DB::beginTransaction();

                    if($investmentVars['status'] === 'cancelled'){

                        $investment->update([

                            'status' => $investmentVars['status'],
                            'cancelled_by' => $user->id,
                        ]);

                        //Clean up the investment account records created when creating the investment
                    }
                    else if($investmentVars['status'] === 'closed'){

                        $investment->update([

                            'status' => $investmentVars['status'],
                            'amount_returned' => $investmentVars['amount_returned'],
                            'date_of_recovery' => $investmentVars['date_of_recovery'],
                            'profit' => ($investment->amount - $investmentVars['amount_returned']),
                            'closed_by' => $user->id
                        ]);

                    }
                    else if($investmentVars['status'] === 'settled'){

                        $investment->update([

                            'status' => 'settling'
    
                        ]);

                        $settleInvestment = RecordAccountInvestments::dispatch($investment);
                        
                    }

                DB::commit();

            return $investment;
        }
        catch (\Throwable $e) {
                
            Log::error('Error updating transaction:'. $e->getMessage());

            DB::rollBack();

            return false;
        }

    }

}