<?php

namespace App\Http\Resources\Transactions;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Administration\AccountResource;

class AccountTransactionResource extends JsonResource
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
            'account' => new AccountResource($this->whenLoaded('account')),
            'transaction_reference' => $this->transaction_reference,
            'amount' => $this->amount,
            'date_of_transaction' => $this->date_of_transaction,
            'method_of_payment' => $this->method_of_payment,
            'details' => $this->data,
            'created_at' => $this->created_at,
        ];
    }
}
