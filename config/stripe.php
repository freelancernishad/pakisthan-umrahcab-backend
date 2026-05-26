<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stripe API Credentials
    |--------------------------------------------------------------------------
    */
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Route Settings
    |--------------------------------------------------------------------------
    */
    'route_prefix' => 'v1/payments/stripe',
    'route_middleware' => ['api'],

    /*
    |--------------------------------------------------------------------------
    | Model Mapping
    |--------------------------------------------------------------------------
    */
    'models' => [
        'payment' => null, // Override this in your project (e.g., \App\Models\Payment::class)
        'log' => \FreelancerNishad\Stripe\Models\StripeLog::class,
    ],
];
