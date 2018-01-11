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
//        dump($params);exit;
        $whereParam[] = "if_deleted = 0";
        if(!empty($params['datemin']) && empty($params['datemax'])) {
            $whereParam[] = "enroll_date >= ".strtotime($params['datemin']);
        }else if(empty($params['datemin']) && !empty($params['datemax'])) {
            $whereParam[] = "enroll_date <= ".strtotime($params['datemax']);
        }else if(!empty($params['datemin']) && !empty($params['datemax'])) {
            $whereParam[] = " enroll_date between ". strtotime($params['datemin']) . " AND " . strtotime($params['datemax']);
        }
        if(!empty($params['keyword'])) {
            $keyword = $params['keyword'];
            $whereParam[] = "(username like '%".$keyword."%' OR email like '%".$keyword."%' OR tel like '%".$keyword."%')";
        }
        $user = new User();
        $whereStr = implode(' AND ', $whereParam);
//        echo $whereStr;exit;
        $total = $user->where($whereStr)->count();
//        echo $user->getLastSql();exit;
        $employees = $user->where($whereStr)->paginate();
//        echo $user->getLastSql();exit;
        $this->assign('employees', $employees);
        $this->assign('total', $total);
        return $this->fetch('index/index');

    }
}
