### 说明

类 Java JDK 的动态代理，有拦截器、自动注入、注入工厂等功能

- 拦截器： AOP，调用被代理类的方法时，可提供一个拦截器，自定义方法被调用前、调用后的操作内容
- 自动注入： 类属性类型如果被定义了，就自动把该属性注入
- 注入工厂： 使用 PHP 8 Attribute 功能，在类属性声明上方指定创建该属性的工厂，可自定义属性的具体类。类似 Java 中的 FactoryBean

> 调用 demo 在 `test/Test.php` 中

### 运行测试脚本

```
composer dump-autoload

php test/Test.php
```
