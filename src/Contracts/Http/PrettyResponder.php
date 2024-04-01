<?php

namespace Hyhy\RequestLogger\Contracts\Http;

interface PrettyResponder
{
    /**
     * Return an unauthorized response from the application
     *
     * @param string|array $message
     * @param array $headers
     * @param int $code
     * @return \Illuminate\Http\Response
     */
    public function unauthorized($message = 'Unauthorized', array $headers = [], $code = 1);

    /**
     * Return a forbidden response from the application
     *
     * @param string|array $message
     * @param array $headers
     * @param int $code
     * @return \Illuminate\Http\Response
     */
    public function forbidden($message = 'Forbidden', array $headers = [], $code = 1);

    /**
     * Return a bad request response from the application
     *
     * @param string|array $message
     * @param array $trace
     * @param array $headers
     * @param int $code
     * @return \Illuminate\Http\Response
     */
    public function bad_request($message = 'Invalid request data', array $trace = null, array $headers = [], $code = 1);

    /**
     * Return a not found response from the application
     *
     * @param string|array $message
     * @return \Illuminate\Http\Response
     */
    public function not_found($message = 'Request not found');

    /**
     * Return a method not allow response from the application
     *
     * @param string|array $message
     * @param array|null $trace
     * @return \Illuminate\Http\Response
     */
    public function method_not_allowed(array $trace = null, $message = 'Method not allow');

    /**
     * Return a no content response from the application
     *
     * @param string|array $message
     * @param array $headers
     * @param int $code
     * @return \Illuminate\Http\Response
     */
    public function no_content($message = 'Data not found', array $headers = [], $code = 1);

    /**
     * Return a successful response from the application
     *
     * @param string|array $data
     * @param array $headers
     * @param int $code
     * @param int $httpCode
     * @return \Illuminate\Http\Response
     */
    public function success($data = '', array $headers = [], $code = 0, $httpCode = 200);

    /**
     * Return a failed response from the application
     *
     * @param string|array $message
     * @param array $trace
     * @param int $code
     * @param array $headers
     * @param int $httpCode
     * @return \Illuminate\Http\Response
     */
    public function fail($message = 'Unable to process request', $code = 1, array $trace = null, array $headers = [], $httpCode = 500);

    /**
     * Return a new response from the application
     *
     * @param int $code
     * @param string|array $message
     * @param string|array $data
     * @param array $trace
     * @param array $headers
     * @param int $httpCode
     * @return \Illuminate\Http\Response
     */
    public function respond($code = 0, $message = null, $data = null, array $trace = null, array $headers = [], $httpCode = 200);
}