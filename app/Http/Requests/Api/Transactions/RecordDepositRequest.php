<?php

namespace App\Http\Requests\Api\Transactions;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use Str;

class RecordDepositRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(Auth::user()->can('record-deposit')){

            return true;
        }

        else{

            return false;
        }
    }

    protected function prepareForValidation(){

        $user = Auth::user();

        $this->merge([ 

            'created_by' => $user->id,
            'amount' => (int)str_replace(',', '', $this->amount),
            'transaction_reference' => Str::uuid(),
        
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    
    public function rules(): array
    {
        return [
            
            'method_of_payment' => 'required|in:bank_transfer,agent_banking,mobile_money',
            'bank' => 'required_if:method_of_payment,bank_transfer',
            'account_name' => 'required_if:method_of_payment,bank_transfer',
            'payment_rail' => 'required_if:method_of_payment,bank_transfer',
            'account_number' => 'required_if:method_of_payment,bank_transfer',
            'account_identifier' => 'required|exists:accounts,account_identifier',
            'mobile_money_name' => 'required_if:method_of_payment,mobile_money',
            'phone_number' => 'required_if:method_of_payment,mobile_money|starts_with:256|size:12',
            'proof_of_payment'  => 'required|file',
            'amount' => 'required|int',
            'date_of_transaction' => 'required|date',
            'created_by' => 'required',
            'transaction_reference' => 'required',


        ];
    }
}
