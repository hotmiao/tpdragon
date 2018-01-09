<?php
namespace app\index\controller;

use think\Controller;
use think\facade\Session;

class Index extends Controller
{
    public function index()
    {
        if(!Session::get('login')) {
            $this->redirect('login/index/index');
        }
        return $this->fetch();
    }

    public function welcome()
    {
        return $this->fetch();
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }

    public function test()
    {
        Session::clear();
//        Session::set('shaojie', '1111333322');
//        return Session::get('shaojie');
    }
}
