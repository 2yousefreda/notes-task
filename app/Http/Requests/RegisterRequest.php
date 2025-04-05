<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            "email"=> ["required","email","unique:users,email"],
            "name"=> ["required","string","max:255"],
            "domain"=> ["required","string","max:255","unique:domains,domain"],
            "password"=> ["required","string","min:8","confirmed"],
           
        ];

    }
    public function prepareForValidation(){
        $this->merge([
            'domain' =>  $this->domain.'.'.config('tenancy.central_domains')[0],
        ]);
    }
}
