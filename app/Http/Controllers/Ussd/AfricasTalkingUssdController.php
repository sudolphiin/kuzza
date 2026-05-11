<?php

namespace App\Http\Controllers\Ussd;

use App\Http\Controllers\Controller;
use App\Services\Ussd\AfricasTalkingUssdService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class AfricasTalkingUssdController extends Controller
{
    /**
     * Africa's Talking USSD gateway hits this action (POST with sessionId, phoneNumber, text).
     *
     * GET is supported only for manual checks (browser/curl) that TLS and routing work;
     * production traffic from AT is always POST.
     *
     * Registered paths (see RouteServiceProvider::mapUssdRoutes):
     *   • POST/GET /api/ussd/africastalking  — primary; paste {APP_URL}/api/ussd/africastalking in AT.
     *   • POST/GET /ussd/africastalking     — alternate without /api.
     *   • POST /                             — only if AT callback URL has no path (legacy).
     *
     * @see \App\Services\Ussd\AfricasTalkingUssdService Menu logic and CON/END responses.
     * @see config/ussd.php Env keys for printed callback URL (ussd:callbacks).
     */
    public function handle(Request $request, AfricasTalkingUssdService $ussd): Response
    {
        if ($request->isMethod('GET')) {
            $lines = [
                'Kuzza USSD: endpoint OK.',
                'Africa\'s Talking must POST with sessionId, phoneNumber, text (empty on first dial).',
                'Callback paths: /api/ussd/africastalking — /ussd/africastalking — POST / (root).',
                'Check storage/logs/laravel.log for ussd.webhook.hit when the simulator runs.',
            ];

            return response(implode("\n", $lines), 200, [
                'Content-Type' => 'text/plain; charset=UTF-8',
            ]);
        }

        try {
            $body = $ussd->handle($request);
        } catch (Throwable $e) {
            Log::error('ussd.controller.exception', [
                'exception' => $e::class,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            $body = config('app.debug')
                ? 'END Debug: '.substr($e->getMessage(), 0, 140)
                : 'END Service busy. Try again.';
        }

        if (! is_string($body) || trim($body) === '') {
            $body = 'END Empty response. Try again.';
        }

        $trim = strtoupper(substr(trim($body), 0, 4));
        if ($trim !== 'CON ' && $trim !== 'END ') {
            Log::warning('ussd.controller.bad_body_prefix', ['preview' => substr($body, 0, 80)]);
            $body = 'END Invalid menu. Try again.';
        }

        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
