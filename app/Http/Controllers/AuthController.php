<?php

namespace App\Http\Controllers;

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new token.
     *
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(User $user): string
    {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued
            'exp' => time() + 60*60 // Expiration time
        ];
        
        return JWT::encode($payload, env('JWT_SECRET'));
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param Request   $request
     * @param  \App\User   $user
     * @return JsonResponse
     */
    public function authenticate(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email'     => 'required|email|exists:users,email',
            'password'  => 'required'
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // Verify the password and generate the token
        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'token' => $this->jwt($user)
            ], 200);
        }
        // Bad Request response
        return response()->json([
            'password' => ['The password is invalid.']
        ], 400);
    }
}
