<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 分页公共方法
 * @param $obj
 * @return string
 */
function pagination($obj){
    if(!$obj){
        return '';
    }
    //获取显示的数据
    $params = request()->param();
    //分页显示  render 获取分页显示方法
    return $obj->appends($params)->render();
}

/**
 * 获取栏目id，然后显示名称
 * @param $catId
 * @return bool|string
 */
function getCatName($catId){
    if(!$catId){
        return '';
    }
    $cats  = config('cat.lists');

    return !empty($cats[$catId]) ? $cats [$catId] :'';
}

/**
 * @param $sex
 * @return string
 */
function getSex($sex){
    return $sex == 1 ? '男' : '女';
}

/**
 * @param $sex
 * @return string
 */
function getStatus($status){
    if(!intval($status)){
        return '';
    }

    return $status == 1 ? '正常' : '<span style="color:red;">已删除</span>';
}
/**
 * 是与否 的判断显示
 * @param $str
 * @return string
 */
function isYesNo($str){
    return $str ? '<span style="color:red">是</span>' :'<span style="color:green">否</span>';
}

/**
 * 通用状态方法
 * @param $id
 * @param $sta
 */
function appStatus($id,$sta){
    $controller = request()->controller();

    if($sta != 1 && $sta != 0){
        $sta = -1;
    }
    $url = url($controller . '/status',['id' =>$id]);

    //已发布状态---->下架状态
    if($sta == config('code.status_normal')){
        return "<span class=\"label label-success radius\">已发布</span><td class=\"f-14 td-manage\"><a class=\"c-primary\" onClick=\"del_status(this,id)\" href=\"javascript:;\" title=\"下架\" confirm_url =\"".$url."\"><i class=\"Hui-iconfont\">&#xe6de;</i></a>";
    }
    //待审核状态---->发布或未通过状态
    elseif($sta == config('code.status_padding')){
        return "<span class=\"label label-primary radius\">待审核</span><td class=\"f-14 td-manage\"><a style=\"text-decoration:none\" onClick=\"pending_status(this,id)\" href=\"javascript:;\" title=\"审核\" confirm_url =\"".$url."\"><i class=\"Hui-iconfont\">审核</i></a>";
    }
    //下架状态---->发布状态
    elseif($sta == config('code.status_delete')) {
        return "<span class=\"label label-danger radius\">已下架</span><td class=\"f-14 td-manage\"><a style=\"text-decoration:none\" onClick=\"publish_status(this,id)\" href=\"javascript:;\" title=\"发布\" confirm_url =\"".$url."\"><i class=\"Hui-iconfont\">&#xe603;</i></a>";
    }
    //状态码不在范围之内
    return "<span class=\"label label-default radius\">数据错误</span><td class=\"f-14 td-manage\"><a style=\"text-decoration:none\" onClick=\"\" href=\"javascript:;\" title=\"发布\" confirm_url =\"\"><i class=\"Hui-iconfont\"></i></a>";
}

/**
 *通用api数据接口输出
 * @param int $status 业务状态码,暂定为0和1
 * @param string $message 业务提示信息
 * @param array $data json数据
 * @param int $httpCode http状态码
 */
function apiShow($status = 0, $message = '',$data = [], $httpCode = 400){
    $data = [
       'status' => $status,
       'message' => $message,
       'data' => $data,
    ];
    return json($data,$httpCode);
}

function timeShow($str){
    return date('Y-m-d H:m:s');
}