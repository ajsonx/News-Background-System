<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/18
 * Time: 19:51
 */
namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\Aes;
use app\common\lib\exception\ApiException;

class News extends Common{

    public function index(){
        //栏目id 校验
        $data = input('get.');
        $this->getPageSize($data);

        $whereData['status'] = config('code.status_normal');
        if(empty($whereData['catid'])){
            $whereData['catid'] = input('get.catid',0,'intval');
        }
        //halt($whereData);
        if(!empty($data['title'])){
            $whereData['title'] = ['like','%'.$data['title'].'%'];
        }

        try{
            $total = model('News')->getDataCountByCondition($whereData);
            $news = model('News')->getDataByCondition(
                $whereData,$this->from,$this->size);
        }catch (\Exception $e){
            apiShow(0,'数据获取失败', '',200);
        }
        $result = [
            'total' => $total,
            'page_num' => ceil($total / $this->size),
            'list' => $this->getDealNews($news),
        ];
        return apiShow(1,'OK',$result,200);
    }

    /**
     * 获取新闻详情页
     * @类型 接口
     */
    public function read(){
        $id = input('param.id',0,'intval');
        if(empty($id)){
            return new ApiException('id is empty',404);
        }
        try{
            $news = model('News')->get($id);
            //阅读数自增
            model('News')->where(['id'=> $id])->setInc('read_count');
        }catch (\Exception $e){
            echo $e->getMessage();
        }
        if(empty($news) || $news->status != config('code.status_normal')){
            return new ApiException('新闻不存在',404);
        }
        $cats = config('cat.lists');
        $news->catname = $cats[$news->catid];

        $viewcount =[];
        $viewcount['news_id'] = $id;
        $viewcount['cat_id'] = $news->catid;
        //浏览记录
        if(!empty($this->header['accessusertoken'])) {
            $obj = new Aes();
            $accessUserToken = $obj->decrypt($this->header['accessusertoken']);
            if (preg_match('/||/', $accessUserToken)) {
                list($token, $userId) = explode("||", $accessUserToken);
                $viewcount['user_id'] = $userId;
                //halt($viewcount);
                try{
                    model('UserView')->save($viewcount);
                }catch (\Exception $e){
                    halt($e->getMessage());
                }
            }
        }


        return apiShow(1,'OK',$news,200);
    }
}