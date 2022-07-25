<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'confirm your email'], 404);
        }
        $array = ['message' => ''];

        $creds = $request->only('email', 'password');
        $token = Auth::attempt($creds);


        if ($token) {
            $user['email'] = $creds;
            $array['token'] = $token;
        } else {
            $array['message'] = 'Incorrect username or password';
        }

        return response()->json(['message' => "User Logged in!", 'token' => $array['token'], 'user' => $user], 200);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function unauthorized()
    {
        return response()->json(['error' => "Unauthorized user!"], 401);
    }
}
