<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthApiController extends Controller
{
    /**
     * Login and get API token
     *
     * Authenticate a user with email and password credentials to obtain an API token.
     * This token should be used in the Authorization header for subsequent API requests.
     * Use format: Authorization: Bearer {access_token}
     *
     * @tags Authentication
     * @bodyParam email string required The user's email address. Example: user@example.com
     * @bodyParam password string required The user's password. Example: password123
     * @response 200 {"message": "Login berhasil!", "access_token": "token_string_here", "token_type": "Bearer"}
     * @response 401 {"message": "Email atau password salah"}
     * @response 422 {"message": "Validation error", "errors": {"email": ["The email field is required."]}}
     * @response 500 {"message": "Error saat login"}
     */
    public function getToken(Request $request)
    {
        try {
            $data = $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            if (!Auth::attempt($data)) {
                Log::info('[Auth - API] Email atau password salah');

                return response()->json([
                    'message' => 'Email atau password salah',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('api_token')->plainTextToken;

            Log::info($token);

            return response()->json([
                'message'      => 'Login berhasil!',
                'access_token' => $token,
                'token_type'   => 'Bearer',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Error saat login', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Error saat login',
            ], 500);
        }
    }
}
