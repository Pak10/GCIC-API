<?php

namespace App\Http\Resources\InvestmentManagement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\InvestmentManagement\InvestmentOptionResource;

class InvestmentPlanResource extends JsonResource
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
            'investment_plan' => $this->investment_plan,
            'has_fixed_interest' => $this->has_fixed_interest,
            'fixed_interest' => $this->fixed_interest,
            'investment_options' => InvestmentOptionResource::collection($this->whenLoaded('options')),
            

        ];
    }
}
