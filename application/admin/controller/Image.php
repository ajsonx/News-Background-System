<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/9/1
 * Time: 18:41
 */
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\lib\Upload;
/**
 *
 * Class Image
 * @package app\admin\controller
 */
class Image extends Base{
    /**
     * 图片上传
     *
     */
   public function upload0(){
       $file = Request::instance()->file('file');
       $info = $file->move('upload');
       if($info && $info->getPathname()){
           $data = [
               'status' => 1,
               'message' =>'OK',
               'data' => '/'.$info->getPathname(),
           ];
           echo json_encode($data);exit;
       }
       echo json_encode(['status' => 1,'msg' => '上传失败']);exit;
   }

   public function upload(){
        $image = Upload::image();
        if($image){
            $data = [
                'status' => 1,
                'message' =>'OK',
                'data' => 'https://'.config('aliyun.bucket').'.'.config('aliyun.url').'/'.$image,
            ];
            echo json_encode($data);
        }
   }
}
