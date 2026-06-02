<?php

namespace Nylo\LaravelNyloAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

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
     * @return array<string, string>
     */
    public function rules(): array
    {
        $userModel = Config::string('laravel-nylo-auth.user_model');

        return [
            'email' => "required|email|max:255|exists:{$userModel},email",
            'password' => 'required|string',
        ];
    }
}
