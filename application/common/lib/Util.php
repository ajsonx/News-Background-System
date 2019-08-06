<?php
/**
 * 公共数据转换方法类
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/3
 * Time: 20:49
 */
namespace app\common\lib;


class Util{

    /**
     * 返回给客户端的数据信息格式
     * @param $status
     * @param string $message
     * @param array $data
     */
    public static function show($status , $message = '' , $data = []){
        $result = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];
        echo json_encode($result);
    }
}