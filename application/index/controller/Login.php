<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/4
 * Time: 17:18
 */
namespace app\index\controller;

use app\common\lib\Prefix;
use app\common\lib\redis\Predis;
use app\common\lib\Util;

class Login{

    public function index(){
        $phoneNum = intval($_GET['phone_num']);
        $code = intval($_GET['code']);
        //用户提交是可能删除手机号,所以需要重新校验.这里默认正常提交.参考Send控制器的校验手机号
        if(empty($phoneNum)){
            return Util::show(config('code.error'),'手机号码不能为空');
        }
        if(empty($code)){
            return Util::show(config('code.error'),'验证码不能为空');
        }
        $redisCode = Predis::getInstance()->get(Prefix::smsKey($phoneNum));

        if($redisCode == $code){
            //用户的登陆状态,登陆时间,等信息 写入redis也可以写入Mysql (设计Mysql->User表)
            //有待完善
            $data = [
                'user' => $phoneNum,
                'srcKey' => md5(Prefix::smsKey($phoneNum)),
                'time' => time(),
                'isLogin' => true,
            ];
            try{
                Predis::getInstance()->set($phoneNum,$data);
            }catch (\Exception $e){
                echo $e->getMessage();
            }
            return Util::show(config('code.success'),'登陆成功',$data);
        }else{
            return Util::show(config('code.error'),'验证码不正确');
        }
    }
}