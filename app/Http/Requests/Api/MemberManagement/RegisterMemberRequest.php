<?php

namespace App\Http\Requests\Api\MemberManagement;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use Str;

class RegisterMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(Auth::user()->can('create-member')){

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
            'category' => 'member',
            'registration_reference' => Str::uuid(),
        
        ]);
    }

    public function rules(): array
    {
        return [
            
            'first_name'  => 'required|string|min:3|max:255',
            'last_name' => 'required|string|min:3|max:255',
            'other_name' => 'nullable|string|min:3|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'nationality'  => 'required|string|min:3|max:255',
            'email'  => 'required|email|unique:users,email',
            'phone_number' => 'nullable|size:13|starts_with:+256|unique:users,phone_number',
            'physical_address'  => 'required|string|min:3|max:255',
            'type_of_identification'  => 'required|in:passport,national_identification_number',
            'country_of_residence'  => 'required|string|min:3|max:255',
            'first_name'  => 'required|string|min:3|max:255',
            'bank'  => 'required|string|min:3|max:255',
            'bank_branch'  => 'required|string|min:3|max:255',
            'account_name'  => 'required|string|min:3|max:255',
            'account_number'  => 'required|string|min:3|max:255',
            'mobile_money_number'  => 'required|size:13|starts_with:+256',
            'mobile_money_name'  => 'required|string|min:3|max:255',
            'account_type_id' => 'required|exists:account_types,id',
            'investment_plan_id' => 'required|exists:investment_plans,id',
            'next_of_kin' => 'required|array',
            'next_of_kin.*.first_name' => 'required|string|min:3|max:255',
            'next_of_kin.*.last_name' => 'required|string|min:3|max:255',
            'next_of_kin.*.other_name' => 'nullable|string|min:3|max:255',
            'next_of_kin.*.relationship' => 'required|string|min:3|max:255',
            'next_of_kin.*.phone_number' => 'required|size:13|starts_with:+256',
            'next_of_kin.*.fund_allocation' => 'required|string|min:3|max:255',
            'next_of_kin.*.residence' => 'required|string|min:3|max:255',
            'next_of_kin.*.email' => 'required|email',
            'created_by' => 'required',
            'category' => 'required',
            'registration_reference' => 'required',
            'referred_by' => 'nullable',
        ];
    }
}
