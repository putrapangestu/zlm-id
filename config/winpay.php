<?php

return [
    'base_url' => env('WINPAY_BASE_URL', 'https://sandbox-snap.winpay.id'),
    'partner_id' => env('WINPAY_PARTNER_ID'),
    'channel_id' => env('WINPAY_CHANNEL_ID', 'WEB'),
    'terminal_id' => env('WINPAY_TERMINAL_ID'),
    'private_key_path' => env('WINPAY_PRIVATE_KEY_PATH'),
    'public_key_path' => env('WINPAY_PUBLIC_KEY_PATH'),
    'expiry_minutes' => (int) env('WINPAY_EXPIRY_MINUTES', 60),
    'timeout' => (int) env('WINPAY_TIMEOUT', 15),
];
