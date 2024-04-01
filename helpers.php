<?php

use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Facades\Redis;

if (!function_exists('http_request')) {
    function http_request($method, $uri, $params = null, array $headers = [], $options = [])
    {
        if (!isset($options[RequestOptions::SYNCHRONOUS]) || $options[RequestOptions::SYNCHRONOUS] != false) {
            $options[RequestOptions::SYNCHRONOUS] = true;
        }
        if (!empty($params)) {
            if (strtolower($method) == 'get') {
                if (strpos($uri, '?')) {
                    $uri = $uri . '&' . http_build_query($params);
                } else {
                    $uri = $uri . '?' . http_build_query($params);
                }
            } else {
                if (isset($options['raw']) && $options['raw'] == true) {
                    if (!is_string($params)) {
                        $params = json_encode($params);
                    }
                    $options['body'] = $params;
                    $headers['Content-Type'] = 'application/json';
                    $headers['Content-Length'] = strlen($options['body']);
                } else {
                    $options['form_params'] = $params;
                }
            }
        }

        if (!empty($headers)) {
            $options['headers'] = $headers;
        }

        /** @var ClientInterface $client */
        $client = app(ClientInterface::class);

        $res = $client->requestAsync($method, $uri, $options);
        if ($options[RequestOptions::SYNCHRONOUS] == true) {
            $res = $res->wait();
        }
        return $res;
    }
}

if (!function_exists('responder')) {
    function responder($content = '', array $headers = [], $code = 0, $status = 200)
    {
        $responder = new Hyhy\RequestLogger\Http\PrettyJsonResponder;

        if (func_num_args() === 0) {
            return $responder;
        }

        return $responder->respond($content, $headers, $code, $status);
    }
}

if (!function_exists('getMillisecond')) {
    function getMillisecond()
    {
        return round(microtime(true) * 1000);
    }
}

if (!function_exists('convertMillisecondToDateTime')) {
    function convertMillisecondToDateTime($timestamp, $timezone = "Asia/Ho_Chi_Minh")
    {
        $seconds = $timestamp / 1000;
        return date("Y-m-d H:i:s.v", $seconds);
    }
}

if (!function_exists('getClientIp')) {
    function getClientIp()
    {
        $temp = app('request')->getClientIps();

        return end($temp);
    }
}


