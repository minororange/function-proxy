<?php


namespace Minor\Proxy\Http;


class HttpArgument
{
    public string $baseUri;

    public string $uri;

    public string $method;

    public array $params;

    public array $headers;
}