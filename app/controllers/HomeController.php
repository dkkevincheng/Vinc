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
