<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/18
 * Time: 15:52
 */
namespace app\api\controller;

/**
 * 客户端获取服务端时间
 * 解决时间不一致
 * Class Time
 * @package app\api\controller
 */
class Time{
    public function index(){
        return apiShow(1,'ok',time());
    }
}