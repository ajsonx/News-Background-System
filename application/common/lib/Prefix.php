<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/4
 * Time: 12:51
 */
namespace app\common\lib;

class Prefix{
    /**
     * redis手机号前缀
     * @var string
     */
    public static $smsPre = 'sms_';

    /**
     * 用户名前缀
     * @var string
     */
    public static $userPre = 'sms_';

    /**
     * @param $phoneNum 手机号
     * @return string
     */
    public static function smsKey($phoneNum){
        return self::$smsPre.$phoneNum;
    }

    /**
     * @param $user 用户名
     * @return string
     */
    public static function usrKey($user){
        return self::$userPre.$user;
    }
}