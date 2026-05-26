<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Ekpay API Credentials
    |--------------------------------------------------------------------------
    |
    | These credentials are used to authenticate with the Ekpay Payment Gateway.
    |
    */
    'mer_reg_id' => env('AKPAY_MER_REG_ID'),
    'mer_pass_key' => env('AKPAY_MER_PASS_KEY'),
    'api_url' => env('AKPAY_API_URL', 'https://sandbox.ekpay.gov.bd/ekpaypg/v1'),
    'ipn_url' => env('AKPAY_IPN_URL', null),
    'whitelist_ip' => env('WHITE_LIST_IP', '1.1.1.1'),

    /*
    |--------------------------------------------------------------------------
    | Route Settings
    |--------------------------------------------------------------------------
    |
    | Configure the route prefix and middleware for Ekpay routes.
    |
    */
    'route_prefix' => 'v1/payments/ekpay',
    'route_middleware' => ['api'],

    /*
    |--------------------------------------------------------------------------
    | Model Mapping
    |--------------------------------------------------------------------------
    |
    | You can override the models used by the package for better extensibility.
    |
    */
    'models' => [
        'payment' => null, // Override this in your project (e.g., \App\Models\Payment::class)
        'payment_item' => null, // Override this in your project (e.g., \App\Models\PaymentItem::class)
        'log' => \FreelancerNishad\Ekpay\Models\EkpayLog::class,
    ],
];
