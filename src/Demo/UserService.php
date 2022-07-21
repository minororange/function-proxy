<?php

namespace Minor\Proxy\Demo;


class UserService
{
    private UserQuery $userQuery1;

    #[RpcProperty]
    private UserRpcQuery $userQuery2;

    public function queryUsername1($id)
    {
        echo "执行queryUserName1\n";

        return $this->userQuery1->getUsernameById($id);
    }

    public function queryUsername2($id)
    {
        echo "执行queryUserName2\n";

        return $this->userQuery2->getUsernameById($id);
    }
}