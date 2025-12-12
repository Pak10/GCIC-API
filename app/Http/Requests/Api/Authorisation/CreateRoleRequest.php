<?php

namespace App\Http\Requests\Api\Authorisation;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class CreateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(Auth::user()->can('create-role')){

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
            
            'name' => 'required|unique:roles,name'
        ];
    }
}
