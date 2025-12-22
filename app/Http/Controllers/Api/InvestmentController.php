<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvestmentManagement\InvestmentOption;
use App\Models\InvestmentManagement\InvestmentPlan;
use App\Models\InvestmentManagement\Investment;
use App\Models\InvestmentManagement\InvestmentTransaction;
use App\Models\InvestmentManagement\AccountInvestment;
use App\Models\Administration\Account;
use App\Models\Administration\AccountType;
use App\Http\Resources\InvestmentManagement\InvestmentOptionResource;
use App\Http\Resources\InvestmentManagement\InvestmentPlanResource;
use App\Http\Resources\InvestmentManagement\InvestmentResource;
use App\Http\Resources\InvestmentManagement\InvestmentTransactionResource;
use App\Http\Resources\InvestmentManagement\AccountInvestmentResource;
use App\Http\Resources\Administration\AccountTypeResource;
use App\Http\Requests\Api\Investments\RecordInvestmentRequest;
use App\Http\Requests\Api\Investments\CreateInvestmentPlanRequest;
use App\Http\Requests\Api\Investments\UpdateInvestmentStatusRequest;
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

                'message' => 'Amount requested exceeds the available balance'
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

    public function getInvestments(Request $request)
    {

        $user = Auth::user();

        if(!($user->can('view-investments'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }

        $pageSize = 10;

        if(!empty($request->page_size)){

            $pageSize = $request->page_size;
        }

        $investments = Investment::orderBy('created_at', 'desc')->paginate($pageSize);

        return InvestmentResource::collection($investments);

    }

    public function getInvestment(Request $request, $investmentReference)
    {
        $user = Auth::user();

        if(!($user->can('view-investments'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }

        $investment = Investment::where('transaction_reference', $investmentReference)->first();

        if($investment === null){

            return response()->json([

                'message' => 'Invalid investment reference'
            ],400);

        }

        return new InvestmentResource($investment);

    }


    public function updateInvestmentStatus(UpdateInvestmentStatusRequest $request, $investmentReference)
    {

        $user = Auth::user();

        $validated = $request->validated();

        $investment =  Investment::where('transaction_reference', $investmentReference)->first();

        if($investment === null){

            return response()->json([
    
                'message' => 'Invalid investment reference provided'
            ],400);

        }

        /*
            In this flow, there are specific conditions we need to validate depending on the status
            the user wishes to update to 
        */

        /*
            If the required action is to close the invesment
            1. The user should have permission to close investments
            2. The current investment status should be open
        */

        if($validated['status'] == 'closed'){

            if(!($user->can('close-investment'))){

                return response()->json([
    
                    'message' => 'User does not have access to this resource'
                ],403);
            }

            if($investment->status !== 'open'){

                return response()->json([
    
                    'message' => 'Can only close open investments'
                ],400);

            }
        }

        /*
            In this flow, there are specific conditions we need to validate depending on the status
            the user wishes to update to 
        */

        /*
            If the required action is to cancel the invesment
            1. The user should have permission to cancel investments
            2. The current investment status should be open or still processing
        */

        if($validated['status'] == 'cancelled'){

            if(!($user->can('cancel-investment'))){

                return response()->json([
    
                    'message' => 'User does not have access to this resource'
                ],403);
            }

            if($investment->status === 'settled'){

                return response()->json([
    
                    'message' => 'Cannot cancel settled investments'
                ],400);

            }
        }

        /*
            In this flow, there are specific conditions we need to validate depending on the status
            the user wishes to update to 
        */

        /*
            If the required action is to settle the invesment
            1. The user should have permission to settle investments
            2. The current investment status should be closed
        */

        if($validated['status'] == 'settled'){

            if(!($user->can('settle-investment'))){

                return response()->json([
    
                    'message' => 'User does not have access to this resource'
                ],403);
            }

            if($investment->status !== 'closed'){

                return response()->json([
    
                    'message' => 'Can only setlled closed investments'
                ],400);

            }
        }

        $investment = $this->investmentService->updateInvestmentStatus($investment, $validated, $user);

        if($investment){

            return new InvestmentResource($investment);

        }
        else{

            return response()->json([
    
                'message' => 'Error updating Investment Status'
            ],500);

        }

    }


    public function getAccountTypeInvestment(Request $request, $investmentReference)
    {
        $user =  Auth::user();

        if(!($user->can('view-investments'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }

        $investment =  Investment::where('transaction_reference', $investmentReference)->first();

        if($investment === null){

            return response()->json([

                'message' => 'Invalid Investment provided'

            ],400);
        }

        $accountTypes = AccountType::with([
            'investment' => function ($query) use($investment) {
                $query->where('investment_id', $investment->id);
        }])->get();

        return AccountTypeResource::collection($accountTypes);
   
    }


    public function createInvestmentPlan(CreateInvestmentPlanRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();

        $investmentPlan =  $this->investmentService->storeInvestmentPlan($validated, $user);

        if($investmentPlan){

            return new InvestmentPlanResource($investmentPlan);

        }
        else{

            return response()->json([
    
                'message' => 'Error creating investment pplan'
            ],500);

        }
    }

    public function getInvestmentTransactions(Request $request, $investmentReference)
    {

        $user  = Auth::user();

        if(!($user->can('view-transactions'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }

        $investment = Investment::where('transaction_reference', $investmentReference)->first();

        if($investment === null){

            return response()->json([

                'message' => 'Invalid investment reference'
            ],400);

        }


        $investmentTransactions  = InvestmentTransaction::with(['type', 'investment', 'accountTransaction','account'])
        ->where('investment_id', $investment->id)->orderBy('created_at', 'desc');


        $pageSize = 10;

        if(!empty($request->page_size)){

            $pageSize = $request->page_size;
        }

        $investmentTransactions = $investmentTransactions->paginate($pageSize);

        return InvestmentTransactionResource::collection($investmentTransactions);

    }


    public function getInvestmentTransactionStats(Request $request, $investmentReference)
    {

        $user  = Auth::user();


        if(!($user->can('view-transactions'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }

        $investment = Investment::where('transaction_reference', $investmentReference)->first();

        if($investment === null){

            return response()->json([

                'message' => 'Invalid investment reference'
            ],400);

        }

        $investmentTransactionTotals = InvestmentTransaction::with('type:id,transaction_type')->where('investment_id', $investment->id)
        ->selectRaw('SUM(amount) as total_amount, transaction_type_id')
        ->groupBy('transaction_type_id')
        ->get();

        return response()->json([

            $investmentTransactionTotals
        ]);

    }


    public function getInvestmentLedger(Request $request, $investmentReference)
    {

        $user  = Auth::user();

        if(!($user->can('view-investments'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }

        $investment = Investment::where('transaction_reference', $investmentReference)->first();

        if($investment === null){

            return response()->json([

                'message' => 'Invalid investment reference'
            ],400);

        }


        $accountInvestments =  AccountInvestment::with([
            'account'=> [
            'type',
            'plan']
        ])->where('investment_id', $investment->id);

        $pageSize = 10;

        if(!empty($request->page_size)){

            $pageSize = $request->page_size;
        }

        $accountInvestments = $accountInvestments->paginate($pageSize);

        return AccountInvestmentResource::collection($accountInvestments);


    }
}
