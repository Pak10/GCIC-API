<?php

namespace App\Http\Requests\Api\UserManagement;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use Str;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(Auth::user()->can('create-user')){

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
            'reviewed_by' => $user->id,
            'approved_by' => $user->id,
            'category' => 'administrator',
            'registration_reference' => Str::uuid(),
        
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
            
            'first_name'  => 'required|string|min:3|max:255',
            'last_name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|size:13|starts_with:+256|unique:users,phone_number',
            'role' => 'required|exists:roles,name',
            'reviewed_by' => 'required',
            'approved_by' => 'required',
            'created_by' => 'required',
            'category' => 'required',
            'registration_reference' => 'required',

        ];
    }
}
