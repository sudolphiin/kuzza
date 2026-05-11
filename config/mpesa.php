<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Daraja environment
    |--------------------------------------------------------------------------
    | Sandbox: https://sandbox.safaricom.co.ke
    | Production: https://api.safaricom.co.ke
    */
    'sandbox' => filter_var(env('MPESA_SANDBOX', true), FILTER_VALIDATE_BOOL),

    'consumer_key' => env('MPESA_CONSUMER_KEY'),

    'consumer_secret' => env('MPESA_CONSUMER_SECRET'),

    'shortcode' => env('MPESA_SHORTCODE'),

    'passkey' => env('MPESA_PASSKEY'),

    /*
    |--------------------------------------------------------------------------
    | CustomerPayBillOnline (paybill) or CustomerBuyGoodsOnline (till)
    |--------------------------------------------------------------------------
    */
    'transaction_type' => env('MPESA_TRANSACTION_TYPE', 'CustomerPayBillOnline'),

    /*
    |--------------------------------------------------------------------------
    | STK callback (POST JSON). Register this full URL in Daraja.
    | Example: https://your-domain.com/api/mpesa/stk-callback
    |--------------------------------------------------------------------------
    */
    'stk_callback_url' => env('MPESA_STK_CALLBACK_URL'),

    /*
    |--------------------------------------------------------------------------
    | AccountReference prefix (max length enforced in service)
    |--------------------------------------------------------------------------
    */
    'account_reference_prefix' => env('MPESA_ACCOUNT_REF_PREFIX', 'KUZZA'),

];
