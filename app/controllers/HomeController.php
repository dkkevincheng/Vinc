<?php
class HomeController extends BaseController
{
    public function index()
    {
        // 测试mvc
        $this->view = View::make('home')->with('article', Article::first())->withTitle('一个优雅简单的PHP框架--vinc')->withContent('vinc框架');
        // 测试mail
        // $this->mail = Mail::to(['yourname@gmail.com', 'yourname@qq.com'])->from('yourname <yourname@163.com>')->title('this is your good news!')->content('<h1>Hello~~</h1>');
        // 测试Redis
        // Redis::set('key', 'value', 5, 's');
        // echo Redis::get('key');
    }
}
