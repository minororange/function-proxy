### 使用场景

已知 `a` 、 `b` 两个方法中需要开启事务处理逻辑， `c` 方法又同时调用了 `a` 、`b` 方法， `c` 方法也需要开启事务。

```php
function a(){
    //开启事务
}

function b(){
    //开启事务
}

function c(){
    //开启事务
    a();
    b();
}
```

-   如果将开启事务的逻辑写在各自方法的代码中，那么 `c` 方法执行的时候就开启了三个事务，导致事务不统一。

---

### 方法代理

-   逻辑代码

    ```php
    class TestService
    {

        /**
        * @date 2018/07/25
        * @author ycz
        *
        * @before echo "开始a方法的事务<br>";
        * @after echo "方法执行成功，提交a方法的事务<br>";
        * @exception echo "方法执行异常，回滚a方法的事务<br>";
        */
        public function a()
        {
            echo "执行需要事务的逻辑a <br/>";
        }

        /**
        * @date 2018/07/25
        * @author ycz
        *
        * @before echo "开始b方法的事务<br>";
        * @after echo "方法执行成功，提交b方法的事务<br>";
        * @exception echo "方法执行异常，回滚b方法的事务<br>";
        * @throws \Exception
        */
        public function b()
        {
            echo "执行需要事务的逻辑b <br/>";

            throw new \Exception("b方法事务抛异常");
        }

        /**
        * @date 2018/07/25
        * @author ycz
        *
        * @before echo "开始c方法的事务<br>";
        * @after echo "方法执行成功，提交c方法的事务<br>";
        * @exception echo "方法执行异常，回滚c方法的事务<br>";
        * @throws \Exception
        */
        public function c()
        {
            $this->a();
            $this->b();
        }
    }
    ```

*   执行代码

    ```php
        $functionProxy = new FunctionProxy(TestService::class);

        echo '调用a方法：<br/>';
        $functionProxy->a();
        echo '<hr/>调用b方法：<br/>';
        $functionProxy->b();
        echo '<hr/>调用c方法：<br/>';
        $functionProxy->c();
    ```

*   执行结果

    ```html
    调用a方法：
        开始a方法的事务
        执行需要事务的逻辑a
        方法执行成功，提交a方法的事务

    调用b方法：
        开始b方法的事务
        执行需要事务的逻辑b
        方法执行异常，回滚b方法的事务

    调用c方法：
        开始c方法的事务
        执行需要事务的逻辑a
        执行需要事务的逻辑b
        方法执行异常，回滚c方法的事务
    ```

### 使用说明

-   在方法注释上加上 `@before` 、 `@after` 、`@exception` 、`@replace` 标签

    -   顾名思义 `before` 就是当前方法`执行之前`所执行的代码（例：将事务的开启放在这里）

    -   `after` 就是当前方法`执行成功`所执行的代码（例：将事务的提交放在这里）

    -   `exception` 就是当前方法`执行异常`所执行的代码（例：将事务的回滚放在这里）

    -   `replace` 替换当前方法中的逻辑，返回 `replace` 中的结果，后续 `@before` 、 `@after` 、`@exception` 都不会执行

-   示例：

    ```php
       /**
        * @date 2018/07/25
        * @author ycz
        *
        * @before echo "开始a方法的事务<br>";
        * @after echo "方法执行成功，提交a方法的事务<br>";
        * @exception echo "方法执行异常，回滚a方法的事务<br>";
        */
        public function a()
        {
            echo "执行需要事务的逻辑a <br/>";
        }
    ```

-   注意事项

    -   注释写能够执行的 `php` 代码，如果代码太长可先将代码封装为函数，注释写调用代码即可。

    -   调用函数时应该写完整的类名

        -   例：

            ```php
            /**
            * @date 2018/07/25
            * @author ycz
            *
            * @before (new \modules\index\service\TestService)->before();
            * @after (new \modules\index\service\TestService)->after();
            * @exception (new \modules\index\service\TestService)->exception();
            */
            public function a()
            {
                echo "执行方法a <br/>";
            }
            ```

    -   普通执行不会调用注释中的代码，只有通过方法代理类 `FunctionProxy` 代理调用才会执行，调用示例参见 `执行代码`，也可参考 `index` 模块 `ProxyController`

---

### 注释方法参数说明

-   如果需要代理执行的方法中需要参数，比如 `before` 中调用某个处理的函数，需要当前的方法参数，则应该用以下写法：

    ```php
    /**
     * @date 2018/07/25
     * @author ycz
     *
     * @before \modules\index\service\TestService->c(*);
     * @after echo "方法执行成功，提交a方法的事务<br>";
     * @exception echo "方法执行异常，回滚a方法的事务<br>";
     */
    public function a()
    {
        $arr = func_get_args();
        var_dump($arr);
        echo "执行需要事务的逻辑a <br/>";
    }
    ```

    -   注意事项

        -   不要加 `new` 关键字，在方法括号中加上 `*` 号，如果不加 `*` 则表示该方法不需要参数，并且需要 `new` 关键字

        -   在 `c` 中使用 `func_get_args` 可以拿到传入 `a` 方法中的所有参数，并且包含 `a` 方法所在对象， `a` 方法执行时的异常

        -   参数顺序：

            -   无异常时：`func_get_args` 第一个为 `a` 方法所在对象。

            -   有异常时：第一个为 `Exception` 对象，`a` 方法所在对象顺延一位。

            -   其余传入 `a` 方法的参数在以上两个参数后面

---
