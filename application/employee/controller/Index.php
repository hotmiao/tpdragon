<?php
namespace app\employee\controller;

use app\common\model\User;
use think\Controller;
use think\Db;
use think\facade\Validate;

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

    /**
     * @return mixed
     * 雇员详情
     */
    public function show()
    {
        $id = input('id');
        $employee = $this->userModel->get($id);
        $this->assign('employee', $employee);
        return $this->fetch();
    }

    public function search()
    {
        $params = input('post.');
        $userModel = new User();
        $query = $userModel->where('if_deleted', 0);
        $totalQuery = $userModel->where('if_deleted', 0);
        if(!empty($params['datemin']) && !empty($params['datemax'])) {
            if(!Validate::egt($params['datemax'], $params['datemin'])) {
                $this->error('开始日期不能小于结束日期！');
            }
            $query = $query->where('enroll_date', 'between time', [$params['datemin'], $params['datemax']]);
            $totalQuery = $totalQuery->where('enroll_date', 'between time', [$params['datemin'], $params['datemax']]);
        }else if(!empty($params['datemin']) && empty($params['datemax'])) {
            $query = $query->where('enroll_date', '>= time', $params['datemin']);
            $totalQuery = $totalQuery->where('enroll_date', '>= time', $params['datemin']);
        }else if(!empty($params['datemax']) && empty($params['datemin'])) {
            $query = $query->where('enroll_date', '<= time', $params['datemax']);
            $totalQuery = $totalQuery->where('enroll_date', '<= time', $params['datemax']);
        }
//        else if(empty($params['datemin']) && empty($params['datemax'])) {
//            $this->redirect('index/index');
//        }
        if(!empty($params['keyword'])) {
            $query->where('username like %'.$params['keyword'].'% or tel like %'.$params['keyword'].'% or email like %'.$params['keyword'].'%');
//            ->whereOr('tel', 'like', $params['keyword'])
//            ->whereOr('email', 'like', $params['keyword']);
        }
        $employees = $query->paginate();
        $total = $totalQuery->count();
        if(!empty($employees)) {
            $this->assign('employees', $employees);
        }
        if(isset($total)) {
            $total = $total >= 0 ? $total : 0;
            $this->assign('total', $total);
        }else {
            $this->assign('total', 0);
        }
        return $this->fetch('index/index');
    }
}
