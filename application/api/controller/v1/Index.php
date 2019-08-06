<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/18
 * Time: 16:39
 */
namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\exception\ApiException;
use think\Controller;
use think\Exception;

class Index extends Common{

    /**
     * app获取首页接口
     *
     */
    public function index(){

        $heads =  model('News')->getIndexHeadNormalNews();

        $heads = $this->getDealNews($heads);

        $position = model('News')->getIndexPositionNews();

        $position = $this->getDealNews($position);

        $result = [
            'heads' => $heads,
            'positions' => $position,
        ];

        return apiShow(1,'OK',$result,200);
    }

    /**
     * 初始化
     * 1.app版本对比
     */
    public function init(){
        try {
            $version = model('Version')
                ->where(['app_type' => $this->header['apptype']])
                ->order(['id' => 'desc'])
                ->limit(1)
                ->find();
        } catch (\Exception $e) {
            return new ApiException('查询app更新失败',404);
        }

        if(empty($version)){
            return new ApiException('version查询为空',404);
        }
        if($version->version > $this->header['version'] ){
            $version->is_update = $version->is_force == 1 ? 2 : 1; //2强制更新
        }else{
            $version->is_update = 0;
        }

        $actives = [
            'version' => $this->header['version'],
            'app_type' => $this->header['apptype'],
            'did' => $this->header['did'],
            'model' => $this->header['model'],
            'update_time' => time(),
        ];
        try{
            model('AppActive')->add($actives);
        }catch (\Exception $e){

        }
        return apiShow(1,'OK',$version,200);

    }
}