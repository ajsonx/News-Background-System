<?php
/**
 * Created by PhpStorm.
 * User: ajsonx
 * Date: 2018/11/26
 * Time: 09:38
 */
namespace app\api\controller\v1;
use app\common\model\Member;
use app\api\controller\Common;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;

/**
 * 登陆权限控制
 * 一些需要登陆的模块都要继承（点赞，评论）
 * 判断access_user_token 解密后是否于数据库中的token一致，以及过期时间
 * Class AuthBase
 * @package app\api\controller\v1
 */
class AuthBase extends Common{
    /**
     * 当前用户信息
     * @var array
     */
    public $userMsg = [];

    /**
     * 初始化
     * @return mixed
     */
    public function _initialize()
    {
        parent::_initialize();
        if($this->isLogin() == false){
            throw new ApiException('您没有登陆',405);
        }
    }

    /**
     * 判断用户是否登陆
     * @return bool
     */
    public function isLogin(){
        if(empty($this->header['accessusertoken'])){
            return false;
        }
        $obj = new Aes();

        $accessUserToken = $obj->decrypt($this->header['accessusertoken']);

        if(empty($accessUserToken)){
            return false;
        }
        if(!preg_match('/||/',$accessUserToken)){
            return false;
        }
        list($token,$id) = explode("||",$accessUserToken);
        $this->userMsg = Member::get(['token' => $token]);
        //halt($this->userMsg);
        if(!$this->userMsg || $this->userMsg['status'] != 1){
            return false;
        }
        if(time() > $this->userMsg['time_out']){
            return false;
        }
        return true;
    }
}