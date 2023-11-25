<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Nylo\LaravelNyloAuth\Http\Controllers\Controller;

/**
* Class ApiController
**/
class ApiController extends Controller
{
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function getUser()
    {
    	$user = Auth::user();

    	return response()->json($user);
    }
}
