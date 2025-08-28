<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, int $maxAttempts = 60, int $decayMinutes = 1): Response
    {
        $key = $this->resolveRequestSignature($request);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            return response()->json([
                'success' => false,
                'message' => 'Demasiadas solicitudes. Intente nuevamente en ' . $seconds . ' segundos.',
                'retry_after' => $seconds
            ], 429)->header('Retry-After', $seconds);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        $response = $next($request);

        // Agregar headers informativos
        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => RateLimiter::remaining($key, $maxAttempts),
        ]);

        return $response;
    }

    /**
     * Resolve the request signature.
     */
    protected function resolveRequestSignature(Request $request): string
    {
        if ($user = $request->user()) {
            return sha1('rate_limit_' . $user->id);
        }

        return sha1('rate_limit_' . $request->ip() . '|' . $request->userAgent());
    }
}
