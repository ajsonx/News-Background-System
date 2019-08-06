<?php
/**
 * redis配置
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/4
 * Time: 12:48
 */
return [
    'host' => '127.0.0.1',
    'port' => '6379',
    'auth' => '',
    'timeout' => 5, //连接超时时间 5s
    'out_time' => 300, //数据有效时间  (redis是内存中的数据,重启就消失,需要存储到磁盘上)
];
