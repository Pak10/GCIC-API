<?php

namespace App\Http\Resources\InvestmentManagement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\InvestmentManagement\InvestmentResource;
use App\Http\Resources\Transactions\AccountTransactionResource;
use App\Http\Resources\Transactions\TransactionTypeResource;
use App\Http\Resources\Administration\AccountResource;

class InvestmentTransactionResource extends JsonResource
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
            'amount' => $this->amount,
            'date_of_transaction' => $this->date_of_transaction,
            'account_transaction' => new AccountTransactionResource($this->whenLoaded('accountTransaction')),
            'transaction_type' => new TransactionTypeResource($this->whenLoaded('type')),
            'investment' => new InvestmentResource($this->whenLoaded('investment')),
            'account' => new AccountResource($this->whenLoaded('account')),
        ];
    }
}
