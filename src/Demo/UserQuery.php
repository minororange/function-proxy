<?php

namespace Minor\Proxy\Demo;

class UserQuery
{
    public function getUsernameById($id)
    {
        return "我是用户ID为{$id}的用户名";
    }
}