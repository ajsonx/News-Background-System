<?php
namespace app\admin\controller;
use app\common\model\AdminUser;
use think\Controller;
class Admin extends Controller
{
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
                $this->success('id='.$id.'success');
            }
            else{
                $this->error('error');
            }

        }
        else return $this->fetch();
    }
}