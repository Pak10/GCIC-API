<?php

namespace App\Http\Resources\InvestmentManagement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentResource extends JsonResource
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
            'transaction_reference' => $this->transaction_reference,
            'date_of_investment' => $this->date_of_investment,
            'amount' => $this->amount,
            'amount_returned' => $this->amount_returned,
            'profit' => $this->profit,
            'share_profit' => $this->share_profit,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'investment_option' => new InvestmentOptionResource($this->whenLoaded('option')),
        ];
    }
}
