<?php

namespace app\admin\controller;

use think\Controller;

/**
 * 后台基础类库
 * Class Base
 * @package app\admin\controller
 */
class Base extends Controller
{
    /**
     * page 默认当前页，一定要默认为空
     * size 分页大小
     * @var string
     */
    public $page = '';
    public $size = '';
    /**
     * 当前分页
     * @var int
     */
    public $from = 0;

    /**
     * 数据库表名
     * @var string
     */
    public $model = '';

    /**
     * 初始化
     */
    public function _initialize()
    {
        $islogin = $this->isLogin();

        if (!$islogin) {
            return $this->redirect('login/index');
        }
    }

    /**
     * 判断是否登陆
     * @return bool
     */
    public function isLogin()
    {
        $user = session(config('admin.session_user'), '', config('admin.session_user_scope'));

        if ($user && $user->id) {
            return true;
        }
        return false;
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

    /**
     * @param int $id
     * @param $model
     * @return mixed
     * 通用删除逻辑，不是假删除
     */
    public function delete($id = 0,$mod = '')
    {
        $this->model = $mod ==''? $this->request->controller():$mod;
        if(!intval($id)){
            return $this->result('','0','ID不合法');
        }
        try{
            $res = model($this->model)->where(['id' => $id ])->delete();
        }catch (\Exception $e){
            return $this->result('','0',$e->getMessage());
        }
        if($res){
            return $this->result(['jump_url' => $_SERVER['HTTP_REFERER'] ],'1','OK');
        }
        return $this->fetch();
    }

    /**
     * 通用状态逻辑
     * @param int $id
     */
    public function status($id = 0,$status = 0)
    {

        $this->model = !empty($this->model) ? $this->model : $this->request->controller();
        //判断ID是否是整数

        if(!intval($id)){
            return $this->result('','0','ID不合法');
        }
        //判断表中是否存在ID
        try{
            $res = model($this->model)->get(['id' => intval($id)] );
        }catch (\Exception $e){
            return $this->result('','0',$e->getMessage());
        }
        if(!$res){
            return $this->result('','0','ID不存在');
        }
        //将ID的status改为1，表示已发布；
        try{
            $res = model($this->model)->save(['status' => $status],['id' => $id]);
        }catch (\Exception $e){
            return $this->result('','0',$e->getMessage());
        }
        //向common.js发送当前url
        if($res){
            return $this->result(['confirm_url' => $_SERVER['HTTP_REFERER'] ],'1','OK');
        }
    }




}