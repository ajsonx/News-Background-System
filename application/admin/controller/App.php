<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/27
 * Time: 16:22
 */
namespace app\admin\controller;

/**
 * 版本管理相关
 * Class App
 */
class App extends Base
{
    /**
     * 版本显示首页内容
     * @return mixedw
     */
    public function index(){
        try{
            $res = model('Version')->where(['status'=>1])->select();
        }catch (\Exception $e){
            echo $e->getMessage();
        }
        return $this->fetch('',[
            'version' => $res
        ]);
    }

    /**
     * 版本添加
     * @return mixed
     */
    public function add(){
        if(request()->isPost()){
            $this->model = 'Version';
            $data = input('param.');
            $data['create_time'] = time();
            $data['status'] = 1;
            try {
                $member = model($this->model)->save($data);
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            return $this->result([
                'jump_url' => url('version/index')],
                1, '添加用户成功');
        }else{
            return $this->fetch();
        }

    }

    /**
     * 版本删除
     * @return mixed|void
     */
    public function vDelete(){
        $data = input('param.');
        try{
            $id = model('Version')
                    ->where('id',$data['id'])
                    ->setField('status',0);
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
        if($id){
            return $this->result([
                'jump_url' => url('app/index')
            ], 1, '删除成功');
        }else{
            return $this->error('删除失败');
        }

    }

    /**
     * 版本内容编辑
     * @return mixed
     */
    public function edit(){
        $this->model = 'Version';

        if (request()->isPost()) {
            $data = input('param.');
            $id = $data['id'];
            try {
                $res = model($this->model)
                    ->where('id', $id)
                    ->setField($data);
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }

            return $this->result([
                'jump_url' => url('version/index')],
                1, '修改成功');

        } else if (request()->isGet()) {
            $data = input('param.');
            $id = $data['id'];
            try {
                $res = model($this->model)->where('id', 'eq', $id)->select();
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            return $this->fetch('',
                ['version' => $res]);
        }
    }
}
