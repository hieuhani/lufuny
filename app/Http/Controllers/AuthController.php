<?php

namespace App\Http\Controllers;

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Sign in user with email and password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signIn(Request $request)
    {
        $data = $request->json()->all();

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json([
                'status' => 401,
                'error' => 'Unauthorized',
                'reason' => 'User is not registered'
            ], 401);
        } else {
            if ($user->verifyPassword($data['password'])) {
                return response()->json($this->generateToken($user));
            } else {
                return response()->json([
                    'status' => 401,
                    'error' => 'Unauthorized',
                    'reason' => 'Wrong password'
                ], 401);
            }
        }
    }

    /**
     * Generate user access token
     *
     * @param $user
     * @return array
     */
    private function generateToken($user) {
        $ttl = 2 * 30 * 24 * 60 * 60; // 2 months
        $secret_key = env('TOKEN_SECRET');
        $payload = array(
            'id' => $user->id,
            'exp' => time() + $ttl
        );
        $jwt = JWT::encode($payload, $secret_key);

        return [
            'token' => $jwt,
            'ttl' => $ttl
        ];
    }
}
