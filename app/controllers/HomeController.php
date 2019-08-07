<?php
class HomeController extends BaseController
{
    public function index()
    {
        $this->view = View::make('home')->with('article', Article::first())->withTitle('一个优雅简单的PHP框架--vinc')->withContent('vinc框架');
    }
}
