<?php
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 17/8/5
 * Time: 下午4:37
 */
namespace app\api\controller\v1;

use app\common\lib\Aes;
use app\common\lib\IAuth;

class User extends AuthBase {

    /**
     * 获取用户信息
     * 用户的基本信息加密处理
     */
    public function read() {
        $obj = new Aes();
        $res = model('UserView')->getViewCount($this->userMsg['id']);
        $this->userMsg['vcount'] = $res;
        //halt($this->userMsg);
        return apiShow(config('code.success'), 'ok',$obj->encrypt($this->userMsg),200);
    }

    /**
     * 修改数据
     */
    public function update() {
        $postData  =  input('param.');
        $data = [];

        if(!empty($postData['image'])) {
            $data['image'] = $postData['image'];
        }
        if(!empty($postData['username'])) {
            $data['name'] = $postData['username'];
        }
        if(!empty($postData['sex'])) {
            $data['sex'] = $postData['sex'];
        }
        if(!empty($postData['hobby'])) {
            $data['hobby'] = $postData['hobby'];
        }
        if(!empty($postData['signature'])) {
            $data['signature'] = $postData['signature'];
        }
        if(!empty($postData['password'])) {
            // 传输过程当中也是需要加密
            $data['password'] = IAuth::setPassword($postData['password']);
        }

        if(empty($data)) {
            return apiShow(config('code.error'), '数据不合法', [], 404);
        }

        try {
            $id = model('Member')->save($data, ['id' => $this->userMsg->id]);
            if($id) {
                return apiShow(config('code.success'), 'OK', [], 202);
            }else {
                return apiShow(config('code.error'), '更新失败', [], 401);
            }
        }catch (\Exception $e) {
            return apiShow(config('code.error'), $e->getMessage(), [], 500);
        }

    }
}