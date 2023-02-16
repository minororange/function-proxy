<?php


namespace Minor\Proxy\Http\Attribute;

use Minor\Proxy\Http\HttpArgument;
use Minor\Proxy\Http\HttpCaller;
use Minor\Proxy\Proxy\PropertyFactory;
use Minor\Proxy\Tools\DynamicClassGenerator;

#[\Attribute]
class HttpServiceProperty implements PropertyFactory
{
    public function create($propertyClass)
    {
        $reflectionClass = new \ReflectionClass($propertyClass);

        $baseUri = $reflectionClass->getAttributes(BaseUri::class);

        $httpClient = $reflectionClass->getAttributes(HttpClientAttribute::class);
        /** @var HttpClientAttribute $httpClient */
        $httpClient = $httpClient[0]->newInstance();
        $httpCaller = new HttpCaller($httpClient->getHttpClient());

        $reflectionMethods = $reflectionClass->getMethods();
        foreach ($reflectionMethods as $reflectionMethod) {
            $argument = new HttpArgument();
            $argument->baseUri = $baseUri[0]->getArguments()[0];
            $httpAttribute = $reflectionMethod->getAttributes(HttpAttribute::class);
            /** @var HttpAttribute $httpAttribute */
            $httpAttribute = $httpAttribute[0]->newInstance();
            $argument->uri = $httpAttribute->getUri();
            $argument->headers = $httpAttribute->getHeader();
            $argument->method = $httpAttribute->getMethod();
            $httpCaller->setMethods($reflectionMethod->getName(), $argument);
        }


        $dynamicClassGenerator = new DynamicClassGenerator();

        return $dynamicClassGenerator->create($propertyClass, $httpCaller);
    }
}