<?php

return [
    'secret_key' => env('XENDIT_SECRET_KEY'),
    'public_key' => env('XENDIT_PUBLIC_KEY'),
    'webhook_verification_token' => env('XENDIT_WEBHOOK_VERIFICATION_TOKEN'),
    'is_production' => env('XENDIT_PRODUCTION', false),
];
