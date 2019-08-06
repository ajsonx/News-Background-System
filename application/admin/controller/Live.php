<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/9/16
 * Time: 10:39
 */

namespace app\admin\controller;

use think\Controller;
use app\common\lib\Util;
use app\common\lib\task\Task;
use app\server\Ws;
class Live extends Base
{

    /**
     *
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * NBA球队管理
     * @return mixed
     */
    public function team()
    {

        return $this->fetch();
    }

    /**
     * NBA赛程安排
     * @return mixed
     */
    public function game()
    {
        return $this->fetch();
    }

    /**
     * NBA球员设置
     * @return mixed
     */
    public function player()
    {
        return $this->fetch();
    }

    /**
     * 赛事消息发送
     * @return string
     */
    public function push()
    {
        if (empty($_GET)) {
            Util::show(config('code.error'), "没有数据");
        }
        if (empty($_GET['team_id'])) {
            Util::show(config('code.error'), "请输入id");
        }
        //客户端token
        // => mysql
        $team = [
            1 => [
                'name' => '马刺',
                'logo' => 'http://goer-app.oss-cn-qingdao.aliyuncs.com/nba/Grizzlies_nba_logo_128px_509957_easyicon.net.png',
                'image' => 'http://goer-app.oss-cn-qingdao.aliyuncs.com/nba/cf34ce05e1ed38be369adac53dd03920.png',
            ],
            2 => [
                'name' => '热火',
                'logo' => 'http://goer-app.oss-cn-qingdao.aliyuncs.com/nba/Heat_nba_logo_128px_509959_easyicon.net.png',
                'image' => 'http://goer-app.oss-cn-qingdao.aliyuncs.com/nba/cf34ce05e1ed38be369adac53dd03920.png',
            ],
            3 => [
                'name' => '灰熊',
                'logo' => 'http://goer-app.oss-cn-qingdao.aliyuncs.com/nba/Spurs_nba_logo_128px_509975_easyicon.net.png',
                'image' => 'http://goer-app.oss-cn-qingdao.aliyuncs.com/nba/cf34ce05e1ed38be369adac53dd03920.png',
            ],
            4 => [
                'name' => '小牛',
                'logo' => 'http://goer-app.oss-cn-qingdao.aliyuncs.com/nba/Heat_nba_logo_128px_509959_easyicon.net.png',
                'image' => 'http://goer-app.oss-cn-qingdao.aliyuncs.com/nba/cf34ce05e1ed38be369adac53dd03920.png',
            ],
        ];
        var_dump($_GET);
        $data = [
            'type' => intval($_GET['type']),
            'title' => $team[$_GET['team_id']]['name'],
            'logo' => $team[$_GET['team_id']]['logo'],
            'content' => $_GET['content'],
            'image' => $team[$_GET['team_id']]['image'],
        ];

        /**
         * 优化
         * 添加到ws_Task任务中
         */
        $taskData = [
            'method' => 'sendLive',
            'data' => $data,
        ];
        var_dump($_POST);
        $_POST['ws_server'] = new Ws();
        $res = $_POST['ws_server']->task($taskData);

        return json_encode(['status' => 1]);

    }
}