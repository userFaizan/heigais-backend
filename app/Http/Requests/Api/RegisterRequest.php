<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class  RegisterRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>"required|string|min:3",
            'email'=>"required|string|unique:users,email",
            'password'=>'required|string|min:6|max:18|confirmed',
            'password_confirmation'=>'required|string|min:6|max:18',
            'device'=>'required|string',
            'ip'=>'required|string',
            'location'=>'required|string',
        ];
    }
}
