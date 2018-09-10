<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/9/1
 * Time: 16:51
 */
namespace app\admin\controller;

use think\Controller;

class News extends Base
{
    public function index(){
        $news = model('news')->getNews();
        echo $news;
        return $this->fetch(
           '',[
                'news' => $news
           ]
       );
    }
    public function add(){
        if(request()->isPost()){
            $data = input('post.');

            //入库操作
            try{
                $id = model('News')->add($data);
            }catch (\Exception $e){
                echo $e->getMessage();
                return $this->result('',0,'添加新闻失败');
            }
            //如果有返回，抛送url跳转到首页
            if($id) {
                return $this->result([
                    'jump_url' => url('news/index')
                ], 1, '添加成功');
            }
            else{
                return $this->result('',0,'添加新闻失败');
            }
        }

        //可以放到配置文件里
        else {
            return $this->fetch('', ['cats' => [
                 1 => '头条',
                 2 => '国内',
                 3 => '国际'
                ]
            ]);
        }

    }
}
