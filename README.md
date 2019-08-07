# Vinc

自己动手搭建一个属于自己的MVC框架，实现极简主义设计。风格类似Sinatra。

## 一个优雅简单的框架 : Vinc

+ [composer一个PHP界神器](#composer一个PHP界神器)
+ [MVC框架路由的思考](#MVC框架路由的思考)
+ [设计框架的骨骼](#设计框架的骨骼)
+ [使用ORM提高框架的效率](#使用ORM提高框架的效率)
+ [发送属于你自己的第一封邮件](#发送属于你自己的第一封邮件)
+ [缓存服务器Redis，怎么能少了你呢？](#缓存服务器Redis，怎么能少了你呢？)
+ [把门面做出来，让变美更简单](#把门面做出来，让变美更简单)

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
> 编辑composer.json文件

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

### 设计框架的骨骼

终于我们的框架实现了第一步，已经能通过路由访问不同的页面了。
下面我们要实现的是搭建起来自己的MVC结构。
这就说到了PHP框架另外的价值：

1. 确立开发规范以便于多人协作
2. 使用 ORM、模板引擎 等工具以提高开发效率。

开始编码了～～

```bash
# 新建app文件夹在项目目录，添加controllers、models、views三个文件夹。
# controllers增加两个文件BaseController.php HomeController.php
# BaseController.php设置一些继承的属性
<?php

class BaseController
{
    public function __construct()
    { }
}

# HomeController.php
<?php

class HomeController extends BaseController{
    public function index(){
        echo "this is Vinc!";
    }
}

# 修改Route.php,增加路由
Macaw::get('/', 'HomeController@index');

# 修改composer.json，增加自动加载
"autoload": {
        "classmap": [
            "app/controllers",
            "app/models"
        ],

#重新加载类
composer dump-autoload
```

打开浏览器，输入网址 127.0.0.1:5000 ，你看到配置成功了。我们已经实现了控制器。

> 配置数据库，实现model文件

```bash
# 新建文件 Data.php,这就是操作数据库的model文件。以后介绍使用ORM操作数据库
# 编辑文件，请查看GitHub内容。
# 修改HomeController.php
<?php

class HomeController extends BaseController{
    public function index(){
        // echo "this is Vinc!";
        $db = Data::getIntance();
        $sql = "select * from articles limit 0,1";
        $list = $db->getAll($sql);
        $db->p($list);

    }
}
#重新加载类
composer dump-autoload
```

```bash
# 数据库的代码
# 创建你自己的数据库。修改你的配置，Data.php文件
CREATE TABLE `articles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `articles` (`id`, `title`, `content`)
VALUES
(1,'标题1','<h3>内容111呀~~</h3><p>参与用户调研，有机会获得200元无门槛代金券！~ O(∩_∩)O</p>'),
(2,'标题2','<h3>内容222呀~~</h3><p>联系我有优惠!~ O(∩_∩)O</p>');
# 打开浏览器，输入网址 127.0.0.1:5000
# 看到打印内容，加载成功。现在已经和数据库连通了。
```

现在MVC的M和C都已经实现了。我们现在实现view

```bash
# 添加view目录下的home.php
<?php
foreach ($list as $key => $value) {
    echo "<h1>". $value['title'].'</h1>';
    echo "<p>". $value['content'].'</p>';
}
# 引入文件到控制器
<?php

class HomeController extends BaseController
{
    public function index()
    {
        // echo "this is Vinc!";
        $db = Data::getIntance();
        $sql = "select * from articles limit 0,1";
        $list = $db->getAll($sql);
        require_once VINC_PATH . '/../app/views/home.php';
    }
}
# 刷新浏览器
```

MVC框架本质是一种管理代码的格式。现在几乎所有人都是通过学习某个框架来了解 MVC 的。但是一旦脱离了框架，一个页面也写不出。我认为，框架再成熟，也离不开PHP的基本原理和哲学。不管哪种语言都是为了让人脑这样的超低 RAM 的计算机能够制造出远超人脑 RAM 的大型软件。一个框架驱动程序做的时区是这样的，入口文件通过路由调用控制器，控制器调用模型，模型和数据库交互，返回数据给控制器，控制器在调用视图，视图把数据装载进视图显示给用户，流程结束。

### 使用ORM提高框架的效率

本篇集成一个 ORM Composer包 ORM 就是'Object Relational Mapping'=对象关系映射。ORM的出现是为了帮我们把对数据库的操作变得更加地方便。

```bash
# 编辑composer.json
    "require": {
        "php": ">=7.1.3",
        "noahbuscher/macaw": "dev-master",
        "illuminate/database": "*",
        "filp/whoops": "*"
    }
    # illuminate/database这个是Laravel的ORM 包。我试用了几个著名的ORM，发现还是 Laravel 的 Eloquent 好用！filp/whoops 是我们的错误提示组件包。也是laravel正在使用的。
# 编辑view/home.php
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?></title>
</head>

<body>
    <div class="article">
        <h1><?php echo $article['title'] ?></h1>
        <div class="content">
            <?php echo $article['content'] ?>
        </div>
    </div>
    <ul class="fuckme">
        <li>欢迎使用!</li>
        <li>
            <?php echo $content ?>
        </li>
    </ul>
</body>

</html>
```

```bash
# 编辑BaseController.php
<?php
class BaseController
{
    public function __construct()
    { }
    public function __destruct()
    {
        $view = $this->view;
        if ($view instanceof View) {
            extract($view->data);
            require $view->view;
        }
    }
}

# 编辑HomeController.php
<?php
class HomeController extends BaseController
{
    public function index()
    {
        $this->view = View::make('home')->with('article', Article::first())->withTitle('一个优雅简单的PHP框架--vinc')->withContent('vinc框架');
    }
}

# 编辑models/View.php
<?php
class View
{
    public $view;
    public $data;
    public function __construct($view)
    {
        $this->view = $view;
    }
    public static function make($viewName = null)
    {
        if (!$viewName) {
            throw new InvalidArgumentException("视图名称不能为空！");
        } else {
            $viewFilePath = self::getFilePath($viewName);
            if (is_file($viewFilePath)) {
                return new View($viewFilePath);
            } else {
                throw new UnexpectedValueException("视图文件不存在！");
            }
        }
    }
    public function with($key, $value = null)
    {
        $this->data[$key] = $value;
        return $this;
    }
    private static function getFilePath($viewName)
    {
        $filePath = str_replace('.', '/', $viewName);
        return VINC_PATH . '/../app/views/' . $filePath . '.php';
    }
    public function __call($method, $parameters)
    {
        if (starts_with($method, 'with')) {
            return $this->with(snake_case(substr($method, 4)), $parameters[0]);
        }
        throw new BadMethodCallException("方法 [$method] 不存在！.");
    }
}

# 编辑Article.php
<?php
class Article extends Illuminate\Database\Eloquent\Model
{
    public $timestamps = false;
}

# 刷新类文件
composer dump-autoload
```

启动php-server，刷新浏览器,看到内容正常显示。至此，视图装载器实现完成

### 发送属于你自己的第一封邮件

上面的内容，我们已经搭建出来了自己的框架，有没有觉得很好玩，下面我们就更加进一步。扩充我们的邮件发送系统。

```bash
# 修改composer.json 添加
    "require": {
        "php": ">=7.1.3",
        "noahbuscher/macaw": "dev-master",
        "illuminate/database": "*",
        "filp/whoops": "*",
        "nette/mail": "*"
    }
# 装载插件
composer update

# 引入插件，增加app/models/Mail.php
# 修改BaseController.php
        $mail = $this->mail;
        if ($mail instanceof Mail) {
            $mailer = new Nette\Mail\SmtpMailer($mail->config);
            $mailer->send($mail);
        }
# 调用发送邮件,发送邮件需要邮箱开通smtp
$this->mail = Mail::to(['yourname@gmail.com', 'yourname@qq.com'])->from('yourname <yourname@163.com>')->title('this is your good news!')->content('<h1>Hello~~</h1>');
```

### 缓存服务器Redis，怎么能少了你呢

Redis是一个高性能的 'key-value' 数据库，其'value'支持 'String'、'Map(Hash)'、'list'、'set' 和 'sorted sets'，中文翻译为 字符串、字典（哈希，在'世界上最好的语言PHP' 中属于 '数组' 的一部分）、列表、集合和有序集合。我们可以用 Redis 作为高速缓存，存放系统经常需要访问的数据。相比使用文件作为缓存，Redis 拥有更高的性能、更好地可维护性和更强大的操作 API。
> 我们来扩展redis功能

```bash
# 修改composer.json 添加
    "require": {
        "php": ">=7.1.3",
        "noahbuscher/macaw": "dev-master",
        "illuminate/database": "*",
        "filp/whoops": "*",
        "nette/mail": "*",
        "predis/predis": "*"
    }
# 装载插件
composer update

# 引入插件，增加app/models/Redis.php
# 修改HomeController.php
        Redis::set('key', 'value', 5, 's');
        echo Redis::get('key');
# 运行一次后将上面一行注释掉,不断刷新看'value'是否会在设定的时间结束后从页面上消失。
```

*我们的MVC框架搭建到此基本完善，后续还有很多好的插件，我会在这里进行更新，相关的模块如何使用，我会开设专门的板块讲解，谢谢您的耐心查看和陪伴！*

### 把门面做出来，让变美更简单

使用我们的框架做一个程序
...待续

### 参考文档列表

> 1.[Pinatra/Pinatra](https://github.com/Pinatra/Pinatra)
> 2.[noahbuscher/macaw](https://github.com/noahbuscher/macaw)
> 3.[Illuminate/Database](https://github.com/Illuminate/Database/)
> 4.[filp/whoops](https://github.com/filp/whoops)
> 5.[nrk/predis](https://github.com/nrk/predis)
> 6.[nette/mail](https://packagist.org/packages/nette/mail)

### 如果本文对你有帮助，谢谢您的支持
