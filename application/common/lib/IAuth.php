<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/5/9
 * Time: 21:57
 */

namespace app\common\lib;

/**
 * 方法类封装
 * Class IAuth
 * @package app\common\lib
 */
class IAuth{
    /**
     *用户密码加密
     * @param $data
     * @return string
     */
    public static function setPassword($data){
        return md5($data.config('app.password.pre.salt'));
    }
}