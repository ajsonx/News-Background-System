<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/5/9
 * Time: 21:57
 */

namespace app\common\lib;

/**
 * 加密方法类封装
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
        return md5($data.config('app.password_pre_salt'));
    }

    /**
     * 生成每次请求的sign验证签名
     * @param array $data
     * @return array
     */
    public static function setSign($data = []){
        //把数组按字典序排序
        ksort($data);
        //把data 数组改写为 key1=value&key2=value2 形式
        $string = http_build_query($data);
        //Aes加密
        return (new Aes())->encrypt($string);
    }

    /**
     * 校验sign 中的参数是否和传过来的一致
     * @param $header 传过来的header头信息
     * @return bool
     */
    public static function checkSignPass($header){
        $str = (new Aes())->decrypt($header['sign']);
        if(empty($str)){
            return false;
        }
        parse_str($str,$arr);

        if(!is_array($arr)||empty($arr['did'])){
            return false;
        }
//        if($arr['version'] != $header['version']){
//            return false;
//        }
        if($arr['did'] != $header['did']){
            return false;
        }
        //项目上线时候要修改------时间
        if(time() - ceil($arr['time'] / 1000) < 600){
            return false;
        }
        return true;
    }

    /**
     * 客戶端登陸Token 作用类似于web端登陆的Session
     * @method uniqid microtime,boolean基于当前毫秒时间返回一个唯一性id
     * @param string $phone
     * @return string
     */
    public static function setAppLoginToken($phone = ''){
        $str = sha1(md5(uniqid(md5(microtime(true)),true)).$phone);
        return $str;
    }
}