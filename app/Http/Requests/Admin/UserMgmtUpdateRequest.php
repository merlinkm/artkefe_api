<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserMgmtUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:200',
            'email' => ['required', Rule::unique('users')->ignore(request()->route('id'))],
            'phone' => 'required|regex:/^[0-9]{10}/|max:10',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'role' => 'required'
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Email is required',
            'name.required' => 'Name is required!',
            'phone.required' => 'Phone is required!'
        ];
    }
}
