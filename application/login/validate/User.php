<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/8 0008
 * Time: 下午 7:55
 */

namespace app\login\validate;


use think\Validate;

class User extends Validate
{
    // 是否批量验证
    protected $batchValidate = true;

    protected $rule = [
        'tel'  =>  'require|max:11',
        'password' => 'require',
    ];

    protected $message  =   [
        'tel.require' => '请填写正确的手机号码',
        'tel.max'     => '请填写正确的手机号码',
        'password.require'   => '请填写密码',
    ];

    protected $scene = [
        'login'  =>  ['tel','pwd'],
    ];
}