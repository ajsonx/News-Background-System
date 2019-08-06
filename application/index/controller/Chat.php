<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/16
 * Time: 20:03
 */
namespace app\index\controller;

use app\common\lib\redis\Predis;

class Chat {

    /**
     * 聊天室控制器
     */
    public function index(){
        //判断用户是否登陆
        //数据是否合法
        //获取用户信息
        var_dump($_POST);
        $data = [
           'user' => 131222222,
           'content' => $_POST['content'],
        ];
        foreach ($_POST['ws_server']->ports['1']->connections as $fd){
            $_POST['ws_server']->push($fd,json_encode($data));
        }

    }

    public function getKey(){
        Predis::getInstance()->set('phoen',$_GET['code']);
        var_dump($_GET);
    }

}