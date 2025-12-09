<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvestmentManagement\InvestmentOption;
use App\Models\InvestmentManagement\InvestmentPlan;
use App\Models\InvestmentManagement\Investment;
use App\Models\Administration\Account;
use App\Http\Resources\InvestmentManagement\InvestmentOptionResource;
use App\Http\Resources\InvestmentManagement\InvestmentPlanResource;
use App\Http\Resources\InvestmentManagement\InvestmentResource;
use App\Http\Requests\Api\Investments\RecordInvestmentRequest;
use Auth;
use App\Services\InvestmentService;

class InvestmentController extends Controller
{
    
    public function __construct()
    {
        $this->investmentService = new InvestmentService;
      
    }

    public function getInvestmentOptions(Request $request)
    {
        $user = Auth::user();

        if(!($user->can('view-investment-options'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }

        $investmentOptions = InvestmentOption::orderBy('created_at', 'desc')->get();

        return InvestmentOptionResource::collection($investmentOptions);

    }

    public function getInvestmentPlans(Request $request)
    {
        $user = Auth::user();

        if(!($user->can('view-investment-plans'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }

        $investmentPlans = InvestmentPlan::with('options')->orderBy('created_at', 'desc')->get();

        return InvestmentPlanResource::collection($investmentPlans);

    }


    public function recordInvestment(RecordInvestmentRequest $request)
    {

        $user = Auth::user();

        $validated =  $request->validated();

        /*
            Check if the current investments
        */

        $currentInvestmentTotal = $this->investmentService->currentInvestmentTotal();

        $totalAccountBalance = Account::sum('balance');

        if(($currentInvestmentTotal + $validated['amount']) > $totalAccountBalance){

            return response()->json([

                'message' => 'Amount requested exceeds the avaialable balance'
            ],400);
        }

        $investment = $this->investmentService->storeInvestment($validated);

        if($investment){

            return new InvestmentResource($investment);

        }
        else{

            return response()->json([

                'message' => 'Error Storing investment'
            ],500);
        }

        

    }
}
