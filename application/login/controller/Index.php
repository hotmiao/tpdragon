<?php
namespace app\login\controller;

use app\login\validate\User;
use think\Controller;
use think\facade\Session;

class Index extends Controller
{
    public function index()
    {
        if(Session::get('login')) {
            $this->redirect('index/index/index');
        }
        return $this->fetch();
    }

    public function login()
    {
        $data = input('post.');
        $validate = new User();
        if($validate->scene('login')->check($data)) {
            $model = new \app\common\model\User();
            $user = $model->getUserByTel($data['tel']);
            if($data['password'] == $user->password) {
                Session::set('login', $user);
                $this->success('登录成功', 'index/index/index');
            }else {
                $this->error('手机号或密码错误，请重新输入！');
            }
        }else {
            $this->error($validate->getError());
        }
    }
}
