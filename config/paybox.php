<?php

return [

    /**
     * Base route to access the paybox admin dashboard
     */
    'uri' => 'paybox',

    /**
     * Stripe public key
     */
    'stripePublicKey' => env('STRIPE_PUBLIC'),

    /**
     * currency code and symbol
     */
    'currency' => [
        'code' => 'usd',
        'symbol' => '$'
    ],

    /**
     * Number of days to offer trial with card upfront
     * It should be a number greater than 0 if you offer trial
     * Leave it at 0 if you offer no trial period
     */
    'offerTrial' => 0,

    /**
     * Namespace to your user model
     */
    'userModel' => App\User::class
];