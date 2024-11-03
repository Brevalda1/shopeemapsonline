<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-7jqDqMUWF5PpWhb01kYpyg_j'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-6AUGjhjpRblPK_j6'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => true,
    'is_3ds' => true,
]; 