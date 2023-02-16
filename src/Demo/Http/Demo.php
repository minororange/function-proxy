<?php


namespace Minor\Proxy\Demo\Http;


use Minor\Proxy\Http\Attribute\HttpServiceProperty;

class Demo
{
    #[HttpServiceProperty]
    protected UserHttpService $httpService;

    public function demo()
    {
        $user = $this->httpService->getUserById(['id' => 10086]);

        $result = $this->httpService->updateUser(['id' => 10086, 'name' => 'xxxxx']);
        echo PHP_EOL;
        echo "demo result:\n";
        echo "    getUserById:{$user->toString()}\n";
        echo "    updateUser:{$result->toString()}\n";
        echo PHP_EOL;
    }
}