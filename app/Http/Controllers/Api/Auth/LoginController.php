<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        //

        $user = User::where('email' , $request->email)->first();

        if( !$user || !Hash::check($request->password, $user->password))
        {
            throw ValidationException::withMessages([
                "email" => ['The credentials provided is not correct']
            ]);
        }

        $token = $user->createToken('laravel_api_token')->plainTextToken;

        return response()->json([
            "token" => $token,
            "user" => $user
        ], Response::HTTP_OK);
    }
}
