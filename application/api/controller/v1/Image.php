<?php
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 17/8/5
 * Time: 下午4:37
 */
namespace app\api\controller\v1;

use app\common\lib\Upload;

class Image extends AuthBase {

    public function save() {
        $image = Upload::image();
        if($image) {
           $str = "http://goer-app.oss-cn-qingdao.aliyuncs.com/".$image;
            $data = [
                'status' => 1,
                'message' => 'OK',
                'data' => $str,
                'httpCode' => 200,
            ];
           return apiShow(config('code.success'), 'ok', $str,200);
        }
    }
}