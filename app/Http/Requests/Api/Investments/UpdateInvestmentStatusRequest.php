<?php

namespace App\Http\Requests\Api\Investments;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UpdateInvestmentStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(Auth::user()->can('view-investments')){

            return true;
        }

        else{

            return false;
        }
    }

    protected function prepareForValidation(){


        $this->merge([ 

            'amount_returned' => (int)str_replace(',', '', $this->amount_returned),

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
            
            'status' => 'required|in:closed,cancelled,settled',
            'date_of_recovery' => 'required_if:status,closed|nullable|date',
            'amount_returned' => 'required_if:status,closed|nullable|int|gt:0',
            'discretionary_accounts' => 'required_if:status,settled|array',
            'discretionary_accounts.*.account_identifer' => 'required|exists:accounts,account_identifier',
            'discretionary_accounts.*.amount_returned' => 'required|int|gt:0',
            'interest' => 'required_if:status,settled|nullable',
        ];
    }
}
