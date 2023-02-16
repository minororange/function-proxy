<?php


namespace Minor\Proxy\Demo\Http;


use Minor\Proxy\Http\Attribute\BaseUri;
use Minor\Proxy\Http\Attribute\HttpAttribute;
use Minor\Proxy\Http\Attribute\HttpClientAttribute;
use Minor\Proxy\Http\Contract\HttpServiceInterface;
use Minor\Proxy\Http\HttpResponseEntity;

#[BaseUri('http://localhost:8123')]
#[HttpClientAttribute(FakeHttpClient::class)]
interface UserHttpService extends HttpServiceInterface
{
    #[HttpAttribute('/getUserById', 'GET')]
    public function getUserById(array $params): HttpResponseEntity;

    #[HttpAttribute('/updateUser', 'POST')]
    public function updateUser(array $params): HttpResponseEntity;
}