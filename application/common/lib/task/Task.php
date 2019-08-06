<?php
/**
 * 分发异步任务
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/6
 * Time: 19:42
 */

namespace app\common\lib\task;

use app\common\lib\ali\Sms;
use app\common\lib\redis\Predis;
use app\common\lib\Prefix;
use app\common\lib\Util;

class Task
{

    /**
     * 发送短信
     * @param $data
     * @return bool
     * @param $server 服务端对象
     */
    public function sendSms($data, $server)
    {
        try {
            $res = Sms::sendSms($data['phone'], $data['code']);
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }

        if ($res->Code === 'OK') {
            Predis::getInstance()->set(Prefix::smsKey($data['phone']), $data['code'], config('redis.out_time'));
        } else {
            return false;
        }
        return true;
    }

    /**
     * @param $data
     * @param $server
     * @return array
     */
    public function sendLive($data, $server)
    {
        var_dump($data);
        $client = \app\common\lib\redis\Predis::getInstance()
            ->sMember('live');
        foreach ($client as $fd) {
            //可能会出现服务端关闭时客户端关闭 redis数据清楚出现操作异常
            try {
                echo "pushing...";
                $server->push($fd, json_encode($data));
            } catch (\Exception $e) {

            }
        }
    }
}