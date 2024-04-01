<?php

namespace Hyhy\RequestLogger\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;

class LoggingWithTrace
{
    /**
     * Customize the given logger instance.
     *
     * @param \Illuminate\Log\Logger $logger
     * @return void
     */
    public function __invoke($logger)
    {
        $appName = config('app.name');
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new LineFormatter(
                "[%datetime%] $appName %level_name%: %message% %context% %extra%",
                "Y-m-d H:i:s.u"
            ));
            $handler->pushProcessor(new IntrospectionProcessor(Logger::DEBUG, ['Illuminate']));
        }
    }
}