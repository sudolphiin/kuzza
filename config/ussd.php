<?php

return [

    /*
    |--------------------------------------------------------------------------
    | USSD / Africa's Talking — developer handoff
    |--------------------------------------------------------------------------
    |
    | Laravel does NOT register your callback with Africa's Talking. You paste the
    | full HTTPS URL into the AT dashboard. This file only builds the same string
    | for tooling (e.g. php artisan ussd:callbacks) from .env.
    |
    | HTTP entry points (same controller): routes/ussd.php + routes/ussd_public.php,
    | registered in RouteServiceProvider::mapUssdRoutes().
    |   • Preferred:  {APP_URL}/api/ussd/africastalking   (routes/ussd.php under api prefix)
    |   • Alternate:  {APP_URL}/ussd/africastalking       (routes/ussd_public.php, no /api)
    |   • Legacy:     POST {APP_URL}/                     (root — only if AT is configured that way)
    |
    | Env — public URL used for ussd:callbacks and config('ussd.*'):
    |   1. USSD_CALLBACK_URL     Full webhook URL if set (overrides everything below).
    |   2. USSD_PUBLIC_APP_URL   Origin only, if webhooks must differ from APP_URL (CDN/proxy).
    |   3. APP_URL               Default public origin (must match how AT reaches this app).
    |
    | M-Pesa STK is separate: MPESA_STK_CALLBACK_URL → config/mpesa.php → Daraja.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | public_app_url — origin for printed URLs (no path)
    |--------------------------------------------------------------------------
    */
    'public_app_url' => rtrim((string) env('USSD_PUBLIC_APP_URL', env('APP_URL', 'http://localhost')), '/'),

    /*
    |--------------------------------------------------------------------------
    | africastalking_webhook_url — suggested full URL for AT dashboard (POST)
    |--------------------------------------------------------------------------
    | Not used by Laravel to call AT; use ussd:callbacks or this key in your own docs.
    */
    'africastalking_webhook_url' => env('USSD_CALLBACK_URL')
        ?: rtrim((string) env('USSD_PUBLIC_APP_URL', env('APP_URL', 'http://localhost')), '/').'/api/ussd/africastalking',

    /*
    |--------------------------------------------------------------------------
    | USSD response limits (GSM 7-bit, Africa's Talking / most gateways)
    |--------------------------------------------------------------------------
    */
    'max_response_length' => (int) env('USSD_MAX_RESPONSE_LENGTH', 182),

    /*
    |--------------------------------------------------------------------------
    | Session TTL (seconds) — USSD sessions should be short-lived
    |--------------------------------------------------------------------------
    | Stored in Laravel Cache (use redis for production scale).
    */
    'session_ttl_seconds' => (int) env('USSD_SESSION_TTL', 300),

    /*
    |--------------------------------------------------------------------------
    | Cache store for USSD session keys (must respond within ~10s to gateways)
    |--------------------------------------------------------------------------
    | Store name from config/cache.php (e.g. file, redis). Default "file" so a
    | misconfigured or unreachable Redis (when CACHE_DRIVER=redis) does not block
    | every dial for minutes. Set USSD_CACHE_STORE=redis when Redis is reliable.
    */
    'cache_store' => env('USSD_CACHE_STORE', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Default school for lookups when the request is not bound to a subdomain
    |--------------------------------------------------------------------------
    | Students can share admission numbers across schools; set this in single-
    | school deployments. Leave null only if you accept first match (risky).
    */
    'default_school_id' => env('USSD_DEFAULT_SCHOOL_ID'),

    /*
    |--------------------------------------------------------------------------
    | Parent role in users.role_id (Infix / Kuzza convention)
    |--------------------------------------------------------------------------
    */
    'parent_role_id' => (int) env('USSD_PARENT_ROLE_ID', 3),

    /*
    |--------------------------------------------------------------------------
    | users table columns used for parent MSISDN match (USSD hot path)
    |--------------------------------------------------------------------------
    | Comma-separated users columns for MSISDN match, e.g. phone_number or
    | phone_number,phone. If unset, defaults to phone_number only (no DB schema
    | introspection — Schema::hasColumn is too slow for USSD ~10s gateways).
    */
    'user_phone_columns_env' => env('USSD_USER_PHONE_COLUMNS'),

    /*
    |--------------------------------------------------------------------------
    | users.* columns loaded for parent USSD lookup (SELECT list)
    |--------------------------------------------------------------------------
    | Must exist on your users table. Tweak via USSD_PARENT_USER_SELECT_COLUMNS in .env
    | if you add columns (e.g. academic_id). Never use Schema::hasColumn on this path.
    */
    'parent_user_select_columns' => array_values(array_filter(array_map('trim', explode(',', (string) env(
        'USSD_PARENT_USER_SELECT_COLUMNS',
        'id,full_name,email,username,role_id,school_id,active_status,wallet_balance,phone_number'
    ))))),

    /*
    |--------------------------------------------------------------------------
    | Sandbox / simulator (never enable in production)
    |--------------------------------------------------------------------------
    | Optional simulator fallback parent users.id when testing MSISDN not in DB.
    */
    'sandbox_fallback_parent_user_id' => env('USSD_SANDBOX_FALLBACK_PARENT_USER_ID'),

];
