<?php

namespace Hyhy\RequestLogger\Providers;

use Illuminate\Support\ServiceProvider;
use Hyhy\RequestLogger\Middleware\LogRequest;
use Hyhy\RequestLogger\Support\HttpClient;
use GuzzleHttp\ClientInterface;
use Hyhy\RequestLogger\Logging\LoggingWithTrace;
use Illuminate\Contracts\Http\Kernel;

class HyhyRequestLoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->mergeConfig();
        $this->registerLog();
        $this->app->bind(ClientInterface::class, HttpClient::class);
    }

    private function mergeConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/hyhy_request.php', 'hyhy_request');
    }

    private function registerLog(){
        $this->mergeConfigFrom(__DIR__ . '/../../config/hyhy_logging.php', 'hyhy_logging');

        if(config('hyhy_logging.log_with_trace')){
            $channelLogWithTrace = config('hyhy_logging.channel_log_with_trace');
            $channelLogWithTrace = explode(',', $channelLogWithTrace);

            foreach ($channelLogWithTrace as $channelLog) {
                config(["logging.channels.$channelLog.tap" => [LoggingWithTrace::class]]);
            }
        }

//        if (config('hyhy_logging.log_incoming')){
//            $kernel = app()->make(Kernel::class);
//            $kernel->pushMiddleware(LogRequest::class);
//        }

    }
}
