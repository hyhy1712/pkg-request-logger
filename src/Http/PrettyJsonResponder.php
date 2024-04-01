<?php

namespace Hyhy\RequestLogger\Http;

use Hyhy\RequestLogger\Contracts\Http\PrettyResponder;
use Illuminate\Support\Traits\Macroable;

class PrettyJsonResponder implements PrettyResponder
{
    use Macroable;

    /**
     * {@inheritdoc}
     */
    public function unauthorized($message = 'Unauthorized', array $headers = [], $code = null)
    {
        return $this->respond($code, $message, null, null, $headers, 401);
    }

    /**
     * {@inheritdoc}
     */
    public function forbidden($message = 'Forbidden', array $headers = [], $code = null)
    {
        return $this->respond($code, $message, null, null, $headers, 403);
    }

    /**
     * {@inheritdoc}
     */
    public function bad_request($message = 'Invalid request data', array $trace = null, array $headers = [], $code = 1)
    {
        return $this->respond($code, $message, null, $trace, $headers, 400);
    }

    /**
     * {@inheritdoc}
     */
    public function not_found($message = 'Not found')
    {
        return $this->respond(null, $message, null, null, [], 404);
    }

    /**
     * {@inheritdoc}
     */
    public function method_not_allowed(array $trace = null, $message = 'Method not allow')
    {
        return $this->respond(null, $message, null, $trace, [], 405);
    }

    /**
     * {@inheritdoc}
     */
    public function no_content($message = 'No content', array $headers = [], $code = 1)
    {
        return $this->respond($code, $message, null, null, $headers, 204);
    }

    /**
     * {@inheritdoc}
     */
    public function success($data = null, array $headers = [], $code = 0, $httpCode = 200)
    {
        return $this->respond($code, null, $data, null, $headers, $httpCode);
    }

    /**
     * {@inheritdoc}
     */
    public function fail($message = 'Fail', $code = 1, array $trace = null, array $headers = [], $httpCode = 500)
    {
        return $this->respond($code, $message, null, $trace, $headers, $httpCode);
    }

    /**
     * {@inheritdoc}
     */
    public function respond($code = 0, $message = null, $data = null, array $trace = null, array $headers = [], $httpCode = 200)
    {
        return (new PrettyJsonResponse($data, $httpCode, $headers, JSON_UNESCAPED_UNICODE))->withCode($code)->withMessage($message)->withTrace($trace);
    }

}