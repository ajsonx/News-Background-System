<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/9/5
 * Time: 22:22
 */
namespace  app\common\lib;

use OSS\OssClient;
use OSS\Core\OssException;
use think\Request;
use think\Session;
/**
 * Class Upload
 * @package app\common\lib
 */
class Upload {

    /*
     *图片上传
     */
    public static function image(){
        //
        if(empty($_FILES['file']['tmp_name'])){
            exception('404');
        }
        //获取文件名称
        $pathinfo = pathinfo($_FILES['file']['name']);
        //获取文件后缀名
        $ext = $pathinfo['extension'];
        //获取aliyun密钥数组
        $config = config('aliyun');
        //获取文件的缓存路径
        $object = $_FILES['file']['tmp_name'];
        //自定义文件名
        $content = Session::get('adminuser.username',config('admin.session_user_scope')).
            "/".date('Y.m.d').'/'.md5(substr($object,0,5)).rand(0,9999).'.'.$ext;
        //阿里云OSS上传文件
        try {
            $ossClient = new OssClient($config['ak'], $config['sk'], $config['url']);
            $ossClient->uploadFile($config['bucket'], $content, $object);
            return $content;
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
        }
    }

}