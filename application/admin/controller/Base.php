<?php
namespace app\admin\controller;
use think\Controller;

/**
 * 后台基础类库
 * Class Base
 * @package app\admin\controller
 */
class Base extends Controller
{
    /*
     * 初始化控制器
     */
    public function _initialize()
    {
        $islogin = $this->isLogin();

        if(!$islogin){
            return $this->redirect('login/index');
        }
    }
    /**
     * 判断是否登陆
     * @return bool
     */
    public function isLogin(){
        $user = session(config('admin.session_user'),'',config('admin.session_user_scope'));

        if($user && $user->id){
            return true;
        }
        return false;
    }

}