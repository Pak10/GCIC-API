<?php

namespace App\Http\Requests\Api\Investments;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use Str;

class RecordInvestmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(Auth::user()->can('create-investment')){

            return true;
        }

        else{

            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    protected function prepareForValidation(){

        $user = Auth::user();

        $this->merge([ 

            'created_by' => $user->id,
            'amount' => (int)str_replace(',', '', $this->amount),
            'transaction_reference' => Str::uuid(),
        
        ]);
    }

    public function rules(): array
    {
        return [
            
            'date_of_investment' => 'required|date',
            'amount' => 'required|int',
            'created_by' => 'required',
            'investment_option_id' => 'required|exists:investment_options,id',
            'transaction_reference' => 'required',
            
        ];
    }
}
