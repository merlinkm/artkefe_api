<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class UserPersonalInfoRequest extends BaseFormRequest
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

    public function view()
    {
        return [
            //
        ];
    }

    public function store()
    {
        return [
            'name' => 'required|string|max:200',
            'email' => 'required|unique:users',
            'phone' => 'required|regex:/^[0-9]{10}/|max:10',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'required|string|min:8|confirmed'
        ];
    }

    public function update()
    {
        return [
            'name' => 'required|string|max:200',
            'email' => ['required', Rule::unique('users')->ignore($this->route('user'))],
            'phone' => 'required|regex:/^[0-9]{10}/|max:10',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            
        ];
    }

    public function destroy()
    {
        return [
            //
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
