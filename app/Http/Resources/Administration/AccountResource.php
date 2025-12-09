<?php

namespace App\Http\Resources\Administration;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\InvestmentManagement\InvestmentPlanResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'account_identifier' => $this->account_identifier,
            'balance' => $this->balance,
            'total_deposit' => $this->total_deposit,
            'interest_gained' => $this->interest_gained,
            'referral_commission_earned' => $this->referral_commission_earned,
            'investment_plan' => new InvestmentPlanResource($this->whenLoaded('plan')),

        ];
    }
}
