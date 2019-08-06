<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/19
 * Time: 21:10
 */
namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\Aes;
use app\common\lib\exception\ApiException;
use app\common\model\Member;

class Rank extends Common{

    /**
     * 推荐排行接口
     * @return ApiException|\think\response\Json
     */
     public function index(){
        $hobby = 0;
        $arr = config('cat.lists');
        if(!empty($this->header['accessusertoken'])){
            $obj = new Aes();
            $accessUserToken = $obj->decrypt($this->header['accessusertoken']);

            list($token,$id) = explode("||",$accessUserToken);

            try{
                $userMsg = Member::get(['token' => $token]);
            }catch (\Exception $e){
                echo $e->getMessage();
            }

            //获取用户爱好栏目分类
            if(!empty($userMsg->hobby)){
                foreach ($arr as $k => $v){
                    if($v == $userMsg->hobby){
                        $hobby = $k;
                    }
                }
            }
            try{
                $res = model('UserView')->getMaxViewCount($id);
                $rands = model('News')->getNewsRank('',$hobby,$res);
                $rands = $this->getDealNews($rands);
            }catch (\Exception $e){
                return new ApiException('error',400);
            }
        }else{
            try{
                $rands = model('News')->getNewsRank('',$hobby);
                $rands = $this->getDealNews($rands);
            }catch (\Exception $e){

                return new ApiException('error',400);
            }
        }
        return apiShow(1,'OK',$rands,200);
    }
}