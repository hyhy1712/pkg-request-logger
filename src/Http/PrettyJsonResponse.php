<?php

namespace Hyhy\RequestLogger\Http;

use Illuminate\Container\Container;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Contracts\Pagination\Paginator;
use JsonSerializable;

class PrettyJsonResponse extends JsonResponse
{
    protected $parsedData = [];

    /**
     * {@inheritdoc}
     */
    public function __construct($data = null, $status = 200, $headers = [], $options = 0)
    {
        parent::__construct($data, $status, $headers, $options);
        $this->withData($data);
    }

    public function getException()
    {
        return $this->exception;
    }

    protected function reload()
    {
        $isShowTrace = (env('APP_DEBUG', false) == true && !empty($this->trace));
        if (!$isShowTrace && isset($this->parsedData['trace'])) {
            unset($this->parsedData['trace']);
        }

        if (null === $this->getCode()) {
            if (!empty($data = $this->getServiceData())) {
                parent::setData($data);
            } else {
                parent::setData($this->getMessage());
            }
        } else {
            parent::setData($this->parsedData);
        }
    }

    public function getServiceData()
    {
        return $this->parsedData['data'] ?? null;
    }

    public function withData($data)
    {
        if ($data !== null) {
            if ($data instanceof ResourceCollection) {
                $resource = $data->resource;
                if ($resource instanceof Paginator) {
                    $collection = $data->collection->map->toArray(Container::getInstance()->make('request'));
                    $resource->setCollection($collection);
                    $data= $resource;
                }
            }
            if ($data instanceof JsonSerializable) {
                $this->parsedData['data'] = $data->jsonSerialize();
            } elseif ($data instanceof Arrayable) {
                $this->parsedData['data'] = $data->toArray();
            } else {
                $this->parsedData['data'] = $data;
            }

            $this->reload();

        }

        return $this;
    }

    public function getCode()
    {
        return isset($this->parsedData['code']) ? $this->parsedData['code'] : null;
    }

    public function withCode($code)
    {
        if (null !== $code) {
            $this->parsedData['code'] = $code;

            $this->reload();
        }
        return $this;
    }

    public function getMessage()
    {
        return $this->parsedData['message'] ?? null;
    }

    public function withMessage($message)
    {
        if (!empty($message)) {
            $this->parsedData['message'] = $message;

            $this->reload();
        }

        return $this;
    }

    public function getTrace()
    {
        return $this->trace;
    }

    public function withTrace(array $trace = null)
    {
        if (!empty($trace)) {
            $this->parsedData['trace'] = $trace;

            $this->reload();
        }

        return $this;
    }

    public function getRedirect()
    {
        return $this->parsedData['redirect'] ?? null;
    }

    public function withRedirect($redirect)
    {
        if (!empty($redirect)) {
            $this->parsedData['redirect'] = $redirect;

            $this->reload();
        }
        return $this;
    }
}
