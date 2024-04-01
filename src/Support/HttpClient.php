<?php

namespace Hyhy\RequestLogger\Support;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Hyhy\RequestLogger\Logging\MessageFormatter;

class HttpClient extends Client
{
    public function __construct(array $config = [])
    {
        $config['http_errors'] = config('hyhy_request.request_http_errors');
        $config['verify'] = config('hyhy_request.request_verify');
        $config['timeout'] = config('hyhy_request.request_timeout');

        if (config('hyhy_logging.log_outgoing')) {
            $stack = HandlerStack::create();
            $logChannel = app()->get('log');
            $now = getMillisecond();

            $stack->push(
                Middleware::log(
                    $logChannel,
                    new MessageFormatter($now)
                )
            );
            $config['handler'] = $stack;
        }

        parent::__construct($config);
    }
}