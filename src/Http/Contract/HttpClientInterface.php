<?php


namespace Minor\Proxy\Http\Contract;


use Minor\Proxy\Http\HttpArgument;
use Minor\Proxy\Http\HttpResponseEntity;

interface  HttpClientInterface
{
    public function get(HttpArgument $argument): HttpResponseEntity;

    public function post(HttpArgument $argument): HttpResponseEntity;
}