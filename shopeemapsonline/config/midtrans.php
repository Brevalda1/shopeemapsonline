<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY', 'Mid-server-qh4rBGaMRIhTvDXvJnizVhWR'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'Mid-client-mC9P1VXONEzAQyJE'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', true),
    'is_sanitized' => true,
    'is_3ds' => true,
];
