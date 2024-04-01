<?php

namespace Hyhy\RequestLogger\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use DateTime;

class LogRequest
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed|null
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $params = [
                'request_time' => getMillisecond(),
                'headers' => $request->headers->all()
            ];

            $response = $next($request);
            $params['execute_time'] = getMillisecond() - $params['request_time'];

            $this->logRequest($request, $params, $response);
        } catch (\Throwable $exception) {
            Log::error($exception);
        }

        return $response;
    }

    private function logRequest(Request $request, $params, $response)
    {
        $logData = [
            'uri' => strtok($request->getUri(), '?'),
            'method' => $request->method(),
            'gets' => $request->query(),
            'posts' => $request->post(),
            'headers' => $params['headers'],
            'request_time' => convertMillisecondToDateTime($params['request_time']),
            'execute_time' => $params['execute_time'],
            'response_code' => $response->getStatusCode(),
            'client_ip' => getClientIp(),
            'server_add' => $_SERVER['SERVER_ADDR'] ?? '',
            'http_user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        ];

        Log::info("[Log Incoming] " . json_encode($logData, JSON_UNESCAPED_UNICODE));
    }

}