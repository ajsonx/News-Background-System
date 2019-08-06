<?php
namespace app\admin\controller;
use think\Controller;
class Index extends Base
{
    /**
     * 显示
     * @return mixed
     */
    public function index()
    {
        //halt(session(config('admin.session_user'),'',config('admin.session_user_scope')));
        return $this->fetch('index');
    }
    public function welcome(){
        $mes['time'] = date('Y年m月d日 H:m:s',time());

        $user = session(config('admin.session_user'), '',
            config('admin.session_user_scope'));
        $mes['admin'] = $user['username'];
        //var_dump($mes);
        try{
            $mes['news'] = model('News')->count('id');
            $mes['member'] = model('Member')->count('id');
        }catch (\Exception $e){
            $e->getMessage();
        }
        return $this->fetch('',[
            'mes' => $mes,
        ]);
    }

    /**
     * 测试
     * @return mixed
     */
    public function text(){
        return $this->fetch();
    }
}