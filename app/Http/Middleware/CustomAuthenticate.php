<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\Authenticate;


class CustomAuthenticate extends  Authenticate
{
    public function handle($request, Closure $next)
    {
        try {
            // Call the parent handle method to perform the default authentication check
            parent::handle($request, $next);
        }catch(\Exception $e){
            // Customize the JSON response when authentication fails
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage() ?: 'Unauthorized',
            ], 401);
        }
        return $next($request);
    }
}
