<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/4/23
 * Time: 10:50
 */
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\lib\IAuth;
use app\common\validate\AdminUser;
class Login extends Base{
    public function _initialize(){
    }
    public function index(){
        $islogin = $this->isLogin();
        if($islogin){
            return $this->redirect('index/index');
        }else{
            return $this->fetch();
        }

    }
    public function check(){
        if(Request()->isPost()) {
            $data=input('post.');
            if(!captcha_check($data['code'])) {
                $this->error('验证码不正确');
            }

            $validate = validate('AdminUser');
            if(!$validate->check($data)){
                $this->error($validate->getError());
            }
            /*数据库获取用户数据
             * 可能返回空值，需抛出异常
             */
            try{
                $user = model('AdminUser')->get(['username' => $data['username']]);
            }catch (\Exception $e){
                $this->error($e->getMessage());
            }

            if(!$user || $user->status != config('code.status_normal')) {
                $this->error("用户不存在");
            }
            if(md5($data['password']) != $user['password']){
                $this->error("密码不正确");
            }
            $userdata = [
                'last_login_time' => time(),
                'last_login_ip' => request()->ip(),
            ];

            /*登陆后保存用户登陆信息
             *
             */
            try{
                model('AdminUser')->save($userdata, ['id' => $user->id]);
            }catch (\Exception $e){
                $this->error($e->getMessage());
            }

            session(config('admin.session_user'),$user,config('admin.session_user_scope'));
            $this->success("成功",'index/index');

        }else{
            $this->error('NoRequest');
        }
    }
    public function logout(){

        session(null,config('admin.session_user_scope'));

        $this->redirect('login/index');
    }
    public function welcome(){
        return "";
    }
}