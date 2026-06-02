<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Nylo\LaravelNyloAuth\Http\Controllers\Controller;
use Nylo\LaravelNyloAuth\Http\Requests\ForgotPasswordRequest;
use Nylo\LaravelNyloAuth\Http\Requests\LoginRequest;
use Nylo\LaravelNyloAuth\Http\Requests\RegisterRequest;

/**
 * Class AuthController
 **/
class AuthController extends Controller
{
    /**
     * Login user and create token
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        if (! Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['status' => 510, 'message' => 'Invalid login details']);
        }

        $user = Auth::user();

        if ($user === null) {
            return response()->json(['status' => 510, 'message' => 'Invalid login details']);
        }

        event(new Login(Config::string('auth.defaults.guard'), $user, false));

        return $this->authResponse($user);
    }

    /**
     * Register user and create token
     *
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $userModel = Config::string('laravel-nylo-auth.user_model');

        $user = $userModel::create([
            'name' => $request->name ?? '',
            'email' => $request->email,
            'password' => Hash::make($request->string('password')->toString()),
        ]);

        if (! $user instanceof Authenticatable) {
            return response()->json(['status' => 500, 'message' => 'Unable to create user']);
        }

        event(new Registered($user));

        return $this->authResponse($user);
    }

    /**
     * Forgot password
     *
     * @return JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status !== Password::RESET_LINK_SENT) {
            return response()->json(['status' => 403, 'message' => __($status)]);
        }

        return response()->json(['status' => 200, 'message' => __($status)]);
    }

    /**
     * Create token for user and return response
     *
     * @return JsonResponse
     */
    private function authResponse(Authenticatable $user)
    {
        // Create Laravel Sanctum Token
        $token = $user->createToken('app_api')->plainTextToken;

        return response()->json(['status' => 200, 'token' => $token, 'message' => '']);
    }
}
