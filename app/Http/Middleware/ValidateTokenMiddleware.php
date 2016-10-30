<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Firebase\JWT\JWT;

class ValidateTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('X-Access-Token');
        if (!$token) {
            return response()->json([
                'status' => 401,
                'error' => 'Unauthorized',
                'reason' => 'Unauthorized user'
            ], 401);
        }

        $secret_key = env('TOKEN_SECRET');
        try {
            $decoded = (array) JWT::decode($token, $secret_key, array('HS256'));
            $userId = $decoded['id'];
            $user = User::find($userId);
            if ( ! $user) {
                return response()->json([
                    'status' => 401,
                    'error' => 'Unauthorized',
                    'reason' => 'Account is not exists'
                ], 401);
            }
            $request['user'] = $user;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'error' => 'Bad request',
                'reason' => $e->getMessage()
            ], 400);
        }
        return $next($request);
    }
}
