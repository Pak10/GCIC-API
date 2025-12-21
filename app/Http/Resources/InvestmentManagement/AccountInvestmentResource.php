<?php

namespace App\Http\Resources\InvestmentManagement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Administration\AccountResource;

class AccountInvestmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,
            'amount_invested' => $this->amount_invested,
            'amount_returned' => $this->amount_returned,
            'interest_gained' => $this->interest_gained,
            'total_amount_withdrawn' => $this->amount_withdrawn,
            'total_amount_deposited' => $this->amount_deposited,
            'investment_changed' => $this->investment_changed,
            'direct_investment' => $this->direct_investment,
            'has_fixed_interest' => $this->has_fixed_interest,
            'fixed_interest' => $this->fixed_interest,
            'account' => new AccountResource($this->whenLoaded('account')),
        ];
    }
}
