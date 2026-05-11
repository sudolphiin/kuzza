<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| USSD webhooks (no session / no CSRF). Keep this group minimal.
|--------------------------------------------------------------------------
|
| Env → callback URL rules: config/ussd.php. Print URLs: php artisan ussd:callbacks.
|
| Short code (example *384*20156#) is set in Africa's Talking, not in Laravel.
| Production: set APP_URL (or USSD_PUBLIC_APP_URL) to your public HTTPS origin — no ngrok.
| Run `php artisan ussd:callbacks` on the server to print URLs for AT + Daraja.
|
| Use EITHER callback URL (both hit the same controller):
|   https://YOUR-HOST/api/ussd/africastalking   ← recommended
|   https://YOUR-HOST/ussd/africastalking
| M-Pesa STK result: POST https://YOUR-HOST/api/mpesa/stk-callback
|
| Local/ngrok tests: POST /api/ussd/africastalking (not bare POST /).
| Root POST / works if ussd_public.php is loaded; ERR_NGROK_3004 often means malformed
| HTTP upstream — use APP_DEBUG=false on webhook tests and a path URL.
|
| If the simulator still shows Africa's Talking's default welcome:
|   1. Confirm Laravel receives traffic: storage/logs/laravel.log → lines "ussd.webhook.hit"
|      (no lines = callback URL wrong, tunnel down, or AT not POSTing to your host).
|   2. Paste the callback again in the USSD channel (no trailing slash). Save/create channel.
|   3. "Launch Simulator" must use the same app/sandbox where that channel exists.
|
| Africa's Talking fails the session if your app does not respond within ~10 seconds
| ("URL Call Failed. Callback failed to respond before timeout"). Production tips:
|   - Free ngrok URLs often "cold sleep" >10s on first dial — use ngrok paid, a VPS,
|     or keep-alive pings; otherwise AT times out before PHP answers.
|   - USSD_CACHE_STORE=file (default) avoids multi-minute hangs when CACHE_DRIVER=redis
|     but Redis is down/unreachable. Use USSD_CACHE_STORE=redis only when Redis works.
|   - CACHE_DRIVER=redis is fine for the rest of the app once Redis is healthy.
|   - Set USSD_USER_PHONE_COLUMNS=phone_number,phone if you use a legacy `phone` column.
|   - USSD_PARENT_USER_SELECT_COLUMNS lists users table columns for parent lookup (defaults
|     are fast; never rely on Schema::hasColumn on the USSD path — it can take 30–60s).
|   - Run php artisan config:cache route:cache on the server; keep DB in the same region.
|   - Laravel Pulse records every HTTP request by default; USSD routes run
|     OptimizeUssdRequest to stop recording for that request. Set PULSE_ENABLED=false
|     in .env if you do not use Pulse (saves overhead on all routes).
|   - If assigned items do not appear: php artisan ussd:diagnose-parent 0701280676
|     (checks parent_recommended_items vs users.id / legacy sm_parents.id & sm_students.id).
|     Admin: Assign Items → "Repair legacy assignments" if rows used wrong ID columns.
|   - If one sibling has items and another has zero (same parent): assign items to that
|     student in admin, or: php artisan ussd:replicate-assignments --parent-user=6
|       --from-student-user=7 --to-student-user=8 [--dry-run]
|
*/

Route::match(['get', 'post'], 'ussd/africastalking', 'Ussd\AfricasTalkingUssdController@handle');

/*
| M-Pesa Daraja STK callback (POST JSON). Register in Safaricom portal:
|   https://YOUR-HOST/api/mpesa/stk-callback
*/
Route::post('mpesa/stk-callback', 'Mpesa\MpesaStkCallbackController@handle');
