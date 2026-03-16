<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Active Payment Gateway
    |--------------------------------------------------------------------------
    | Options: 'paymongo', 'test'
    */
    'gateway' => env('PAYMENT_GATEWAY', 'paymongo'),

    'paymongo' => [
        'secret_key' => env('PAYMONGO_SECRET_KEY'),
        'public_key' => env('PAYMONGO_PUBLIC_KEY'),
        'webhook_secret' => env('PAYMONGO_WEBHOOK_SECRET'),
    ],

];
