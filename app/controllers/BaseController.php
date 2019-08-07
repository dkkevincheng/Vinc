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
