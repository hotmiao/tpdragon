<?php
namespace app\employee\controller;

use app\common\model\User;
use think\Controller;

class Index extends Controller
{
    private $userModel = null;

    public function initialize()
    {
        if(!$this->userModel) {
            $this->userModel = new User();
        }
    }

    /**
     * @return mixed
     * 雇员列表页
     */
    public function index()
    {
        $userModel = new User();
        $total = $userModel->where('if_deleted' , 0)->order('create_time', 'DESC')->count();
        $employees = $userModel->where('if_deleted' , 0)->order('create_time', 'DESC')->paginate();
        $this->assign('employees', $employees);
        $this->assign('total', $total);
        return $this->fetch();
    }

    public function show()
    {
        $id = input('id');
        $employee = $this->userModel->get($id);
        $this->assign('employee', $employee);
        return $this->fetch();
    }
}
