<?php

return [
    'log_with_trace' => env('HYHY_LOGGING_LOG_WITH_TRACE', true),
    'channel_log_with_trace' => env('HYHY_LOGGING_CHANNEL_LOG_WITH_TRACE', 'kafka,daily,stack'),
    'log_incoming' => env('HYHY_LOGGING_LOG_INCOMING', true),
    'log_outgoing' => env('HYHY_LOGGING_LOG_OUTGOING', true)
];
