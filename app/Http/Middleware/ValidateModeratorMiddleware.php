<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class ValidateModeratorMiddleware
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
        $user = $request['user'];
        if (!($user instanceof User)) {
            return response()->json([
                'status' => 401,
                'error' => 'Unauthorized',
                'reason' => 'Unauthorized user'
            ], 401);
        }
        if (in_array($user->role->name, ['moderator', 'administrator'])) {
            return $next($request);
        }
        return response()->json([
            'status' => 403,
            'error' => 'Forbidden',
            'reason' => 'Forbidden area'
        ], 401);
    }
}
