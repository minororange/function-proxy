<?php


namespace Minor\Proxy\Http\Attribute;

use Minor\Proxy\Http\Contract\HttpClientInterface;
use Minor\Proxy\Http\HttpServiceException;

#[\Attribute]
class HttpClientAttribute
{
    private HttpClientInterface $httpClient;

    /**
     * @throws HttpServiceException
     */
    public function __construct(string $httpClientClass)
    {
        if (!class_exists($httpClientClass)) {
            throw new HttpServiceException('Not found http client class for [' . $httpClientClass . ']');
        }
        $httpClient = new $httpClientClass;
        if (!$httpClient instanceof HttpClientInterface) {
            throw new HttpServiceException('Http client not instanceof AbstractHttpClient:[' . $httpClientClass . ']');
        }
        $this->httpClient = $httpClient;
    }

    /**
     * @return HttpClientInterface
     */
    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }
}