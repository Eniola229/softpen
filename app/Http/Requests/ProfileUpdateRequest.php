<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],

            'class' => ['required', 'string', 'max:50'], // e.g., JSS1, SS2
            'age' => ['required', 'integer', 'min:5', 'max:120'],
            'school' => ['required', 'string', 'max:255'],
            
            'department' => ['nullable', 'string', 'in:Science,Art,Commercial'], // 3 main streams
        
            
            'country' => ['required', 'string', 'max:255'],
            
            'password' => ['nullable', 'string', 'min:8'], 
        ];
    }
}
