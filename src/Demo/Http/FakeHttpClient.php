<?php


namespace Minor\Proxy\Demo\Http;

use Minor\Proxy\Http\Contract\HttpClientInterface;
use Minor\Proxy\Http\HttpArgument;
use Minor\Proxy\Http\HttpResponseEntity;

class FakeHttpClient implements HttpClientInterface
{
    public function get(HttpArgument $argument): HttpResponseEntity
    {
        echo PHP_EOL;
        echo "baseUri:{$argument->baseUri}\n";
        echo "make a GET request to {$argument->uri}\n    params:";
        echo json_encode([$argument->params, $argument->headers]);
        echo PHP_EOL;

        $httpResponseEntity = new HttpResponseEntity();
        $httpResponseEntity->code = 0;
        $httpResponseEntity->message = 'success';
        $httpResponseEntity->data = ['id' => 1, 'name' => 'user'];

        return $httpResponseEntity;
    }

    public function post(HttpArgument $argument): HttpResponseEntity
    {
        echo PHP_EOL;
        echo "baseUri:{$argument->baseUri}\n";
        echo "make a POST request to {$argument->uri}\n    params:";
        echo json_encode([$argument->params, $argument->headers]);
        echo PHP_EOL;

        $httpResponseEntity = new HttpResponseEntity();
        $httpResponseEntity->code = 0;
        $httpResponseEntity->message = 'success';
        $httpResponseEntity->data = ['id' => 2, 'name' => 'user2'];

        return $httpResponseEntity;
    }
}