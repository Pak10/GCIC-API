<?php

namespace App\Http\Requests\Api\Investments;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class CreateInvestmentPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(Auth::user()->can('create-investment-plan')){

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
    public function rules(): array
    {
        return [
            
            'investment_plan' => 'required|string|min:3|max:255',
            'share_profit' => 'required|boolean',
            'mandatory_tithe' => 'required|boolean',
            'has_fixed_interest' => 'required|boolean',
            'fixed_interest' => 'required_if_accepted:has_fixed_interest',
            'investment_options' => 'required|array',
            'inivestment_options.*.investment_option_id' => 'required|exists:investment_options,id',
            'inivestment_options.*.allocation' => 'required|lte:100'
        ];
    }
}
