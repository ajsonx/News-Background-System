<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/18
 * Time: 15:13
 */
namespace app\common\lib;

/**
 * 时间戳
 * Class Time
 * @package app\common\lib
 */
class Time{

    public static function getTime()
    {
        list($t1,$t2) = explode(' ',microtime());
        return $t2.ceil($t1*1000);
    }
}