<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

Route::get('test','api/test/index');
Route::put('test/:id','api/test/update');
Route::resource('test','api/test');

Route::get('api/:ver/cat','api/:ver.cat/read');

Route::get('api/:ver/index','api/:ver.index/index');

Route::get('api/:ver/init','api/:ver.index/init');

Route::resource('api/:ver/news','api/:ver.news');

//排行榜
Route::get('api/:ver/rank','api/:ver.rank/index');

Route::get('api/:ver/sign','api/:ver.login/sign');

Route::post('api/:ver/Identify','api/:ver.Identify/save');

Route::post('api/:ver/login','api/:ver.login/save');

Route::resource('api/:ver/user','api/:ver.user');

Route::post('api/:ver/image', 'api/:ver.image/save');
//点赞
Route::post('api/:ver/upvote', 'api/:ver.upvote/save');
//点赞
Route::delete('api/:ver/upvote', 'api/:ver.upvote/delete');
Route::get('api/:ver/upvote/:id', 'api/:ver.upvote/read');
//评论
Route::post('api/:ver/comment', 'api/:ver.comment/save');
Route::get('api/:ver/comment/:id', 'api/:ver.comment/read');


Route::post('api/:ver/test', 'api/:ver.test/test');