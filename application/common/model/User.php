<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/8 0008
 * Time: 下午 7:51
 */

namespace app\common\model;


use think\Model;

class User extends Model
{
    protected $pk = 'id';

    public function getUserByTel($tel)
    {
        return self::where('tel', $tel)->find();
    }
}