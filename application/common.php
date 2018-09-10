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
    return '<div class ="imooc_app">'.$obj->appends($params)->render().'</div>';
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
    $cats  = config('cats.lists');

    return !empty($cats[$catId]) ? $cats [$catId] :'';
}

/**
 * 是与否 的判断显示
 * @param $str
 * @return string
 */
function isYesNo($str){
    return $str ? '<span style="color:red">是</span>' :'<span style="color:green">否</span>';
}