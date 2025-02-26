<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
     * @param  \Nylo\LaravelNyloAuth\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
    	if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
			return response()->json(['status' => 510, 'message' => 'Invalid login details']);
		}

		$user = Auth::user();

		event(new Login(config('auth.defaults.guard'), $user, false));

		return $this->authResponse($user);
    }

    /**
     * Register user and create token
     *
     * @param  \Nylo\LaravelNyloAuth\Http\Requests\RegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
     public function register(RegisterRequest $request)
     {
        $userModel = config('laravel-nylo-auth.user_model');
    	$userExists = $userModel::where('email', $request->email)->exists();
		if ($userExists) {
			return response()->json(['status' => 506, 'message' => 'A user already exists with that email']);
		}

		$user = $userModel::updateOrCreate(
			['email' => $request->email],
			[
                'name' => $request->name ?? '',
                'email' => $request->email,
				'password' => Hash::make($request->password),
			]
		);

		if ($user->wasRecentlyCreated) {
			event(new Registered($user));
		}

        return $this->authResponse($user);
    }

    /**
     * Forgot password
     *
     * @param  \Nylo\LaravelNyloAuth\Http\Requests\ForgotPasswordRequest  $request
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
    */
    private function authResponse($user)
    {
    	// Create Laravel Sanctum Token
    	$token = $user->createToken('app_api')->plainTextToken;

        return response()->json(['status' => 200, 'token' => $token, 'message' => '']);
    }
}
