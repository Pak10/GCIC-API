<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvestmentManagement\InvestmentOption;
use App\Models\InvestmentManagement\InvestmentPlan;
use App\Http\Resources\InvestmentManagement\InvestmentOptionResource;
use App\Http\Resources\InvestmentManagement\InvestmentPlanResource;
use Auth;

class InvestmentController extends Controller
{
    
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

        $investmentPlans = InvestmentPlan::orderBy('created_at', 'desc')->get();

        return InvestmentPlanResource::collection($investmentPlans);

    }
}
