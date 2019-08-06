<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/22
 * Time: 19:53
 */
namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\Aes;
use app\common\lib\ali\Sms;
use app\common\lib\IAuth;
use app\common\lib\Prefix;
use app\common\lib\redis\Predis;
use app\common\model\Member;

class Login extends Common {
    /**
     *
     */
    public function save(){
        if(!request()->isPost()){
            return apiShow(config('code.error'),'您提交的参数不合法',[],404);
        }
        $param = input('param.');
        if(empty($param['phone'])){
            return apiShow(config('code.error'),'您提交的手机号为空',[],404);
        }
        //客户端传来的验证码解密,postman暂时不用
        //$param['code'] = (new Aes())->decrypt($param['code']);

        $token = IAuth::setAppLoginToken($param['phone']);
        //1.第一次登陆时 记录token
        //2.第二次登陆从mysql查询token 并更新
        $data = [
            'token' => $token,
            'time_out' => strtotime("+7 days"),
            'update_time' => time(),
        ];


        $user = Member::get(['tel' => $param['phone']]);

        if($user && $user->status == 1) {
            if(!empty($param['password'])) {
                // 判定用户的密码 和 $param['password'] 加密之后

                if(IAuth::setPassword($param['password']) != $user->password) {
                    return apiShow(config('code.error'), '密码不正确', [], 403);
                }
            }
            model('Member')->save($data, ['tel' => $param['phone']]);
        } else {
            if(empty($param['code'])){
                return apiShow(config('code.error'),'您提交的验证码为空',[],404);
            }
            $code = Predis::getInstance()->get(Prefix::$smsPre.$param['phone']);
            if($code != $param['code']){
                return apiShow(config('code.error'),'验证码错误',[],404);
            }
            if(!empty($param['code'])) {
                // 第一次登录 注册数据
                $data['name'] = 'GR用户-'.$param['phone'];
                $data['status'] = 1;
                $data['tel'] = $param['phone'];
                model('Member')->add($data);
            } else {
                return apiShow(config('code.error'), '用户不存在', [], 403);
            }
        }//sA9YGeyzYJmjHgS0lUfKqYwPQR0SPwLQ1Pp1/iPZcL1x1o877quF3iwPjtpQBFGl

        $obj = new Aes();
        $id = Member::where('tel',$param['phone'])->value('id');
        if($id) {
            $result = [
                'token' => $obj->encrypt($token."||".$id),
            ];
            return apiShow(config('code.success'), 'ok', $result,200);
        }else {
            return apiShow(config('code.error'), '登录失败', [], 403);
        }
    }
}