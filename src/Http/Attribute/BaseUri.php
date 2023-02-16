<?php


namespace Minor\Proxy\Http\Attribute;

#[\Attribute]
class BaseUri
{
    private string $uri;

    public function __construct(string $uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}