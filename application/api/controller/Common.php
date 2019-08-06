<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/9/18
 * Time: 18:09
 */
namespace app\api\controller;

use app\common\lib\Aes;
use app\common\lib\exception\ApiException;
use think\Controller;
use app\common\lib\IAuth;
use app\common\lib\Time;

class Common extends Controller{

    /**
     * @var string
     */
    public $header = '';

    public $page = 1;
    public $size = 10;
    public $from = 0;
    /**
     * 初始化控制器
     */
    public function _initialize()
    {
        $this->checkRequestAuth();
    }

    /**
     * 检查每次app请求是否合法
     */
    public function checkRequestAuth(){
        //获取headers头
        $header = request()->header();

        //sign 签名 客户端加密  服务端解密
        //1.基础参数校验 判断sign,app_type,model,app_version 是否为空 不符合给出400
        //2.sing校验，是否符合
        //1542884554881 7mmf0aL9cn0vNuBHFsc4AUNDN5e229uyuXgx7P6kBkk=
        //
//        $data=[
//            'did' => 1,
//            'time' => '1542884554881',
//        ];
//        var_dump($data);
//        $data = http_build_query($data);
//        halt( (new Aes())->encrypt($data));

//        if(empty($header['sign'])||empty($header['version'])||empty($header['did'])){
//            throw new ApiException('请求头异常',400);
//        }
//        if(!in_array($header['app_type'],config('app.apptypes'))){
//            throw new ApiException('apptype不合法',400);
//        }
//        if(!IAuth::checkSignPass($header)){
//            throw new ApiException('状态异常',400);
//        }
        $this->header = $header;
    }

    /**
     * 给当前catid匹配catname
     * @param array $news
     * @return array
     */
    protected function getDealNews($news = []){
        if(empty($news)){
            return [];
        }
        $cats = config('cat.lists');

        foreach ($news as $key => $new){
            $news[$key]['catname'] = $cats[$new['catid']]
                ? $cats[$new['catid']] : '-';
        }

        return $news;
    }

    /**
     * 获取分页page size
     * @param $data
     */
    public function getPageSize($data)
    {
        $this->page = !empty($data['page']) ? $data['page'] : 1;
        $this->size = !empty($data['size']) ? $data['size'] : config(
            'paginate.list_rows'
        );
        $this->from = ($this->page - 1) * $this->size;
    }
}