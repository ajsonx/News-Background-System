<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/27
 * Time: 15:17
 */
namespace app\admin\controller;

use think\Controller;

class Comment extends Base{

    /**
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index(){
        $comment = model('Comment')->select();

        return $this->fetch('',
            [
                'comment' => $comment,
            ]);
    }

    public function response(){
        return $this->fetch();
    }
}