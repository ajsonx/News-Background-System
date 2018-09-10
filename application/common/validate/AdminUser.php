<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/4/22
 * Time: 11:33
 */
namespace app\common\validate;

use think\Validate;

class AdminUser extends Validate{

    protected $rule = [
        'username' => 'require|max:20',
        'password' => 'require|max:20',
    ];
}