<?php

namespace App\Http\Requests\Api\Authorisation;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class AttachPermissionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(Auth::user()->can('attach-permissions')){

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
            
            'permissions' => 'required|array',
            'permissions.*' => 'required|exists:permissions,id',
        ];
    }
}
