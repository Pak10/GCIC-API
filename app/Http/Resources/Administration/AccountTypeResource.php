<?php

namespace App\Http\Resources\Administration;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountTypeResource extends JsonResource
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
            'account_type' => $this->account_type,
            'account_prefix' => $this->account_prefix,
            'description' => $this->description,
            'amount_invested' => $this->whenLoaded('investment', function () {

                return $this->investments[0]->pivot->amount_invested;
            }),
        ];
    }
}
