<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogUssdWebhook
{
    public function handle(Request $request, Closure $next)
    {
        $raw = $request->getContent();
        $jsonKeys = [];
        if (is_string($raw) && str_starts_with(ltrim($raw), '{')) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $jsonKeys = array_keys($decoded);
            }
        }

        Log::info('ussd.webhook.hit', [
            'method' => $request->method(),
            'path' => $request->path() === '' ? '/' : $request->path(),
            'ip' => $request->ip(),
            'content_type' => $request->header('Content-Type'),
            'input_keys' => array_keys($request->all()),
            'json_body_keys' => $jsonKeys,
            'user_agent' => substr((string) $request->userAgent(), 0, 120),
        ]);

        $response = $next($request);

        // Log after the HTTP response is sent so slow disk I/O cannot push Africa's
        // Talking past their ~10s callback timeout (they measure until response complete).
        $path = $request->path() === '' ? '/' : $request->path();
        app()->terminating(function () use ($response, $path): void {
            try {
                $content = $response->getContent();
            } catch (\Throwable $e) {
                Log::warning('ussd.webhook.out_read_failed', ['message' => $e->getMessage()]);

                return;
            }

            Log::info('ussd.webhook.out', [
                'path' => $path,
                'status' => $response->getStatusCode(),
                'body_len' => is_string($content) ? strlen($content) : 0,
                'body_preview' => is_string($content) ? substr($content, 0, 100) : '',
            ]);
        });

        return $response;
    }
}
