# Vinc

自己动手搭建一个属于自己的MVC框架，实现极简主义设计。风格类似Sinatra。

## 一个优雅简单的框架 : Vinc

+ [composer一个PHP界神器](#composer一个PHP界神器)
+ [MVC框架路由的思考](#MVC框架路由的思考)
+ [设计框架的骨骼](#设计框架的骨骼)
+ [使用ORM提高框架的效率](#使用ORM提高框架的效率)
+ [把门面做出来，让变美更简单](#把门面做出来，让变美更简单)
+ [发送属于你自己的第一封邮件](#发送属于你自己的第一封邮件)
+ [缓存服务器Redis，怎么能少了你呢？](#缓存服务器Redis，怎么能少了你呢？)

*本仓库是一个示例代码，如果有兴趣，可以关注作者公众号或者加群讨论。*

### composer一个PHP界神器

说起composer，就要讲PSR规范，也就是FIG。FIG 最初由几位知名 PHP 框架开发者发起，在吸纳了许多优秀的大脑和强健的体魄后，提出了 PSR-0 到 PSR-4 五套 PHP 非官方规范:

+ PSR-0 (Autoloading Standard) 自动加载标准
+ PSR-1 (Basic Coding Standard) 基础编码标准
+ PSR-2 (Coding Style Guide) 编码风格向导
+ PSR-3 (Logger Interface) 日志接口
+ PSR-4 (Improved Autoloading) 自动加载优化标准

Composer 利用 PSR-0 和 PSR-4 以及 PHP5.3 的命名空间构造了一个繁荣的 PHP 生态系统。类似 npm 和 RubyGems，给海量 PHP 包提供了一个异常方便的协作通道。
后期基于composer演化出来的框架，有 Laravel 和 Symfony。
本文也是基于composer创建一个自己的MVC框架。

### MVC框架路由的思考

使用composer初始化项目

```bash
    composer init
```

>使用阿里云的composer仓库

```bash
# 在json文件最后添加
"repositories": {
        "packagist": {
            "type": "composer",
            "url": "https://mirrors.aliyun.com/composer/"
        }
    }
```

安装composer

```bash
composer install

```

开始构建我们自己的路由，路由的选择可以参考github的[搜索结果](https://github.com/search?l=PHP&o=desc&q=router&ref=searchresults&s=stars&type=Repositories&utf8=%E2%9C%93)

本教程我们选择简单易上手的[noahbuscher/macaw](https://github.com/noahbuscher/macaw),大家可以去官网上看看。

```bash
# 安装noahbuscher/macaw
require: {
    "noahbuscher/macaw": "dev-master"
}
# 更新composer
composer update

```

使用插件

```bash
# 项目目录下新建public/index.php
# 代码如下
<?php

// 定义根目录
define("VINC_PATH", __DIR__);
//引入启动文件
require_once VINC_PATH. '/../vendor/autoload.php';

require_once VINC_PATH . '/../src/Route.php';

# 新建src/Route.php
# 代码如下
<?php

use \NoahBuscher\Macaw\Macaw;

Macaw::get('/', function () {
    echo 'Hello world!';
});

# 重新加载下composer类
composer dump-autoload
# 启动php-server
cd public/
php -S 127.0.0.1:5000

```

打开浏览器，输入127.0.0.1:5000 ，看到Hello world!，表示你安装成功。
更多选项可以参考文档，github有详细使用。
> 原理梗概
当php引入composer的自动加载文件后，composer会在内存维护一个全量命名空间，类名到文件名的数组。这样我们在使用某个插件功能的时候，会在数组中找到它。
当Macaw::get，使用get的时候，会由Macaw的一个__callstatic() 接收，这个函数接受两个参数，$method 和 $params，前者是具体的 function 名称，在这里就是 get，后者是这次调用传递的参数，即 Macaw::get('/',function(){...}) 中的两个参数，一个是路径，一个是函数处理。
__callstatic() 做的事情就是分别将目标URL（即 ‘/’）、HTTP方法（即 GET）和回调代码压入 $routes、$methods 和 $callbacks 三个 Macaw 类的静态成员变量（数组）中。
最后由Macaw::dispatch()的方法处理。不能直接匹配到的将利用正则进行匹配。

> 编辑composer.json文件

### 参考文档列表

> 1.[Pinatra/Pinatra](https://github.com/Pinatra/Pinatra)
> 2.[noahbuscher/macaw](https://github.com/noahbuscher/macaw)

### 如果本文对你有帮助，请支持
