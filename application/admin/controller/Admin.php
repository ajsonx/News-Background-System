<?php
namespace app\admin\controller;
use app\common\model\AdminUser;
use think\Controller;
class Admin extends Controller
{

    /**
     * 增加管理员
     * @return mixed
     */
    public function add(){
        if(request()->isPost()) {
            //获取post数据
            $data = input('post.');
            //调用数据规则控制器，检验数据是否合法
            $validate = validate('AdminUser');

            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            //数据处理
            $data['password'] = md5($data['password']);
            $data['status'] = '1';

            //调用数据库模型入库
            try {
                $id = model('AdminUser')->add($data);
            }catch (\Exception $e){
                echo $e->getMessage();
            }
            //检验数据是否唯一
            if($id){
                $this->success('管理用户添加成功');
            }
            else{
                $this->error('管理用户添加成功');
            }

        }
        else return $this->fetch();
    }

    /**
     * 网站管理员列表信息
     */
    public function detail(){
        try{
            $user = model('AdminUser')->select();
            $count = model('AdminUser')->count('id');
        }catch (\Exception $e){
            $this->error("数据查询失败");
        }
        return $this->fetch('',[
            'user' => $user,
            'count' => $count,
        ]);
    }

    /**
     * 网站基础信息设置
     * @return mixed
     */
    public function base(){
       return $this->fetch();
    }
}