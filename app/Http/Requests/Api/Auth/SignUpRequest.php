<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SignUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    protected function prepareForValidation(){

        $this->merge([ 

            'name' => $this->first_name.' '.$this->last_name.' '.$this->other_name,
            'category' => 'member',
        
        ]);
    }

    public function rules(): array
    {
        return [
            
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|unique:users,phone_number|starts_with:+256|size:13',
            'password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
            'password_confirmation' => 'required|same:password',
            'first_name' => 'required|string|min:3|max:255',
            'last_name' => 'required|string|min:3|max:255',
            'other_name' => 'nullable|string|min:3|max:255',
            'name' => 'required',
            'category' => 'required',
        ];
    }
}
