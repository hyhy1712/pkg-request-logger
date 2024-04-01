<?php

namespace Hyhy\RequestLogger\Logging;

use GuzzleHttp\MessageFormatterInterface;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class MessageFormatter implements MessageFormatterInterface
{
    private $requestTime;

    public function __construct($requestTime)
    {
        $this->requestTime = $requestTime;
    }

    public function format(RequestInterface $request, ?ResponseInterface $response = null, ?\Throwable $error = null): string
    {
        $logData = [
            'uri' => strtok($request->getUri(), '?'),
            'method' => $request->getMethod(),
            'gets' => $request->getUri()->getQuery(),
            'posts' => $request->getBody()->__toString(),
            'request_headers' => $this->getRequestHeaders($request),
            'response_headers' => $this->getResponseHeaders($response),
            'response_code' => $this->getResponseCode($response),
            'response_body' => $this->getResponseBody($response),
            'request_time' => convertMillisecondToDateTime($this->requestTime),
            'execute_time' => getMillisecond() - $this->requestTime
        ];

        return "[Log Outgoing] " . json_encode($logData, JSON_UNESCAPED_UNICODE);
    }

    // private function convertParams($params)
    // {
    //     $response = [];
    //     if (!strpos($params, '&')) {
    //         return null;
    //     }
    //     $explodeParams = explode('&', $params);

    //     foreach ($explodeParams as $valueParams) {
    //         if (!strpos($valueParams, '=')) {
    //             continue;
    //         }
    //         $explodeValueParams = explode('=', $valueParams);

    //         $keyParam = $explodeValueParams[0];
    //         $valueParam = $explodeValueParams[1];

    //         $response[$keyParam] = urldecode($valueParam);
    //     }

    //     return $response;
    // }

    private function getRequestHeaders($request)
    {
        return \trim($request->getMethod()
                . ' ' . $request->getRequestTarget())
            . ' HTTP/' . $request->getProtocolVersion() . "\r\n"
            . $this->headers($request);
    }

    private function getResponseHeaders($response)
    {
        return $response ?
            \sprintf(
                'HTTP/%s %d %s',
                $response->getProtocolVersion(),
                $response->getStatusCode(),
                $response->getReasonPhrase()
            ) . "\r\n" . $this->headers($response)
            : 'NULL';
    }

    private function getResponseBody($response)
    {
        if (!$response instanceof ResponseInterface) {
            $result = 'NULL';
            return $result;
        }

        $body = $response->getBody();

        if (!$body->isSeekable()) {
            $result = 'RESPONSE_NOT_LOGGEABLE';
            return $result;
        }

        $result = $response->getBody()->__toString();
        return $result;
    }

    private function getResponseCode($response)
    {
        return $response ? $response->getStatusCode() : 'NULL';
    }

    private function headers(MessageInterface $message): string
    {
        $result = '';
        foreach ($message->getHeaders() as $name => $values) {
            $result .= $name . ': ' . \implode(', ', $values) . "\r\n";
        }

        return \trim($result);
    }
}