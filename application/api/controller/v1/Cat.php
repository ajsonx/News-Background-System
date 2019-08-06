<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/18
 * Time: 15:57
 */
namespace app\api\controller\v1;

use app\api\controller\Common;
/**
 * 客户端app获取栏目信息接口
 * Class Cat
 * @package app\api\controller
 */
class Cat extends Common {

    /**
     * 获取栏目方法
     * @return \think\response\Json
     */
    public function read(){
        $cats = config('cat.lists');
        $res[0] = [
            'catid' => 0,
            'catname' => '推荐',
        ];

        foreach ($cats as $catid => $catname){
            $res[] = [
                'catid' => $catid,
                'catname' => $catname,
            ];
        }
        return apiShow(1,'OK',$res,200);
    }

}