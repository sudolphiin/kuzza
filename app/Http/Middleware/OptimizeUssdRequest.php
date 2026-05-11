<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Pulse\Pulse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Africa's Talking requires the callback URL to respond within ~10 seconds.
 * Pulse (when enabled) records queries/requests on every HTTP hit; Debugbar
 * in dev does similar work — both can push USSD over the gateway timeout.
 */
class OptimizeUssdRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        if (config('pulse.enabled') && app()->bound(Pulse::class)) {
            try {
                app(Pulse::class)->stopRecording();
            } catch (\Throwable) {
                // ignore
            }
        }

        if (class_exists(\Fruitcake\LaravelDebugbar\Facades\Debugbar::class)) {
            try {
                \Fruitcake\LaravelDebugbar\Facades\Debugbar::disable();
            } catch (\Throwable) {
                // ignore
            }
        }

        try {
            DB::connection()->disableQueryLog();
        } catch (\Throwable) {
            // ignore
        }

        return $next($request);
    }
}
