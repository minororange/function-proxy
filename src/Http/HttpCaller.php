<?php


namespace Minor\Proxy\Http;

use Minor\Proxy\Http\Contract\HttpClientInterface;
use Minor\Proxy\Proxy\CallableInterface;

class HttpCaller implements CallableInterface
{
    /**
     * @var HttpArgument[]
     */
    public array $methods = [];

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function call($target, $method, $args)
    {
        return $this->$method(...$args);
    }

    /**
     * @throws HttpServiceUnsupportedRequestMethod
     * @throws HttpServiceMethodNotFoundException
     */
    public function __call($name, $arguments)
    {
        return $this->doCall($name, $arguments);
    }

    /**
     * @throws HttpServiceMethodNotFoundException
     * @throws HttpServiceUnsupportedRequestMethod
     */
    private function doCall($name, $arguments): HttpResponseEntity
    {
        if (!isset($this->methods[$name])) {
            throw new HttpServiceMethodNotFoundException();
        }
        $httpArgument = $this->methods[$name];
        $httpArgument->params = $arguments[0] ?? [];
        return match (strtoupper($httpArgument->method)) {
            'GET' => $this->client->get($httpArgument),
            'POST' => $this->client->post($httpArgument),
            default => throw new HttpServiceUnsupportedRequestMethod(),
        };
    }

    /**
     * @param string $name
     * @param HttpArgument $argument
     */
    public function setMethods(string $name, HttpArgument $argument): void
    {
        $this->methods[$name] = $argument;
    }
}