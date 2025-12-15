<?php

namespace App\Http\Requests\Api\MemberManagement;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UpdateMemberRegistrationStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(Auth::user()->can('view-member-registrations')){

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
            
            'status' => 'required|in:approved,reviewed,rejected',
            'investment_plan_id' => 'required_if:status,approved',
            'account_type_id' => 'required_if:status,approved',
        ];
    }
}
