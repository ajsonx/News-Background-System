<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/9/16
 * Time: 10:39
 */

namespace app\admin\controller;

use think\Controller;

class Member extends Base
{

    /**
     * 获取数据库数据显示
     *
     * @return mixed
     */
    public function index()
    {
        $this->model = 'Member';
        $data = input('param.');
        $query = http_build_query($data);
        $whereData['status'] = [
            'eq', config('code.status_normal')
        ];
        $total = model('Member')->getDataCountByCondition($whereData);
        //获取page,size,from 基类里的属性
        $this->getPageSize($data);

        $pageTotal = ceil($total / $this->size);
        try {
            $member = model('member')->getDataByCondition($whereData, $this->from, $this->size);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        return $this->fetch(
            '', [
            'member' => $member,
            'query' => $query,
            'pageTotal' => $pageTotal,
            'curr' => $this->page,

        ]);
    }

    /**
     * 详细信息显示
     * @return mixed
     * index 页面 member_show 方法传入主键 id
     */
    public function show()
    {
        $this->model = 'Member';
        $data = input('param.');
        $id = $data['id'];
        try {
            $member = model($this->model)->where('id', 'eq', $id)->select();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        return $this->fetch('',
            [
                'member' => $member,
            ]);
    }


    /**
     * 带数据编辑功能
     * @return mixed
     * index 页面 member_edit 方法传入主键 id
     */
    public function edit()
    {
        $this->model = 'Member';

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
                'jump_url' => url('member/index')],
                 1, '修改成功');

        } else if (request()->isGet()) {
            $data = input('param.');
            $id = $data['id'];
            try {
                $member = model($this->model)->where('id', 'eq', $id)->select();
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            return $this->fetch('',
                ['member' => $member,]);
        }
    }

    /**
     * 添加用户功能
     * @return mixed
     */
    public function add()
    {
        $this->model = 'Member';
        if(request()->isPost()){

            $data = input('param.');
            halt($data);
            if($data['province'] == $data['city1']){
                $address = $data['province'];
            }else if($data['city1'] == $data['city2']){
                $address = $data['province'].$data['city1'];
            }else {
                $address = $data['province'].$data['city1'].$data['city2'];
            }
            unset($data['province']);unset($data['city1']);unset($data['city2']);
            $data['address'] = $address;
            $data['status'] = 1;
            $data['create_time'] = time();
            try {
                $member = model($this->model)->save($data);
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            return $this->result([
                'jump_url' => url('member/index')],
                1, '添加用户成功');
        }else if (request()->isGet()) {
            $data = input('param.');
            if(empty($data)){
                return $this->fetch();
            }
            $id = $data['id'];
            try {
                $member = model($this->model)->where('id', 'eq', $id)->select();
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            return $this->fetch('',
                ['member' => $member,]
            );
        }

    }

    /**
     * 修改密码功能
     * @return mixed
     */
    public function changePwd()
    {
        return $this->fetch();
    }

    /**
     * 假删除用户功能
     * @return mixed
     */
    public function mDelete()
    {
        $this->model = 'Member';
        $data = input('param.');
        try {
            $member = model($this->model)
                ->allowField(true)
                ->save(['status' => -1], $data);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        return $this->result([
            'jump_url' => url('member/index')
        ], 1, '删除成功');
    }

    /**
     * 还原用户功能
     * @return mixed
     */
    public function mRestore()
    {
        $this->model = 'Member';
        $data = input('param.');
        try {
            $member = model($this->model)
                ->allowField(true)
                ->save(['status' => 1], $data);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        return $this->result([
            'jump_url' => url('member/memberdelete')
        ], 1, '还原成功');
    }

    /**
     * 删除用户功能
     * @return mixed
     */
    public function memberDelete()
    {
        $this->model = 'Member';
        try {
            $member = model($this->model)->where('status', 'eq', -1)->select();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        return $this->fetch('',
            [
                'member' => $member,
            ]);
    }
}