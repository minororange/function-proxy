<?php


namespace Minor\Proxy\Http;


class HttpResponseEntity
{
    public int $code;
    public string $message;
    public array $data;

    public function toString(): string
    {
        return sprintf(
            "code=%s message=%s data=%s",
            $this->code,
            $this->message,
            json_encode($this->data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }
}