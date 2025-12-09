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
            'date_of_investment' => $this->date_of_investment,
            'amount' => $this->amount,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'investment_option' => new InvestmentOptionResource($this->whenLoaded('option')),
        ];
    }
}
