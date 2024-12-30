<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'mode' => env('PAYPAL_MODE', 'sandbox'), // 'sandbox' or 'live'. Default is 'sandbox'.
    
    'sandbox' => [
        'client_id'     => env('PAYPAL_SANDBOX_CLIENT_ID', ''),  // PayPal Sandbox Client ID
        'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET', ''), // PayPal Sandbox Client Secret
        'app_id'        => env('PAYPAL_SANDBOX_APP_ID', 'APP-80W284485P519543T'), // Default Sandbox App ID
    ],

    'live' => [
        'client_id'     => env('PAYPAL_LIVE_CLIENT_ID', ''), // PayPal Live Client ID
        'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET', ''), // PayPal Live Client Secret
        'app_id'        => env('PAYPAL_LIVE_APP_ID', ''), // PayPal Live App ID
    ],

    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Default 'Sale'. Options: 'Sale', 'Authorization', 'Order'
    
    'currency' => env('PAYPAL_CURRENCY', 'USD'), // Default currency is 'USD'
    
    'notify_url' => env('PAYPAL_NOTIFY_URL', ''), // Set the notification URL for your application
    
    'locale' => env('PAYPAL_LOCALE', 'en_US'), // Language for the gateway. e.g., 'en_US', 'es_ES', etc.
    
    'validate_ssl' => env('PAYPAL_VALIDATE_SSL', true), // Validate SSL when creating the API client. Default is true.
];
