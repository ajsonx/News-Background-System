<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/22
 * Time: 21:06
 */
namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\ali\Sms;
use app\common\lib\Prefix;
use app\common\lib\redis\Predis;

class Identify extends Common{

    /**
     * @var string
     */
    public $code = '';

    /**
     * net base HttpParamBody : H
     * ttpParamBody{url='api/v1/identify',
     * httpType='http.type.identify', tag='LoginFragment',
     * map={id=ok http3.RequestBody$2@8f21f8c}, header={os=7.1.1,
     * did=000000000000000,
     * sign=L+t5GdsUApySforo3RvmoRuINS7XZpHW6zR7EXg3eMHhznWzQMpgjZPs4GcwH/F0,
     * model=GoogleNexus7.1.0, version=1.0, app_type=android}}
    2018-11-26 19:01:22.526 1988-1988/com.wiggins.teaching D/wzly: ╚══════════
     */
    public function save(){
        if(!request()->isPost()){
            return apiShow(config('code.error'),'提交数据不合法',[],403);
        }
        $phone = input('post.id');
        $validate = validate('code');
        //校验手机号
        if(!$validate->check(input('post.'))){
            return apiShow(config('code.error'),'手机格式不合法',[],403);
        }

        $code = rand(1000,9999);
        $res = Sms::getInstance()->sendSms($phone,$code);
        if($res->Code == 'OK'){

            Predis::getInstance()->set(Prefix::smsKey($phone), $code,
                config('redis.out_time'));
            return apiShow(config('code.success'),'发送成功',[],201);
        }
        return apiShow(config('code.error'),'发送失败',[],403);
    }
}