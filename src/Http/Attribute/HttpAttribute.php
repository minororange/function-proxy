<?php


namespace Minor\Proxy\Http\Attribute;

#[\Attribute]
class HttpAttribute
{
    private string $uri;
    private string $method;
    private array $header;

    public function __construct(string $uri = '', string $method = '', array $header = [])
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->header = $header;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getHeader(): array
    {
        return $this->header;
    }
}