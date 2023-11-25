<?php

namespace Nylo\LaravelNyloAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
        $userModel = config('laravel-nylo-auth.user_model');
        return [
            'email' => "required|email|max:255|exists:{$userModel},email",
            'password' => 'required|string',
        ];
    }
}
