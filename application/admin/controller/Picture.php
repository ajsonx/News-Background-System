<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/9/16
 * Time: 17:15
 */
namespace app\admin\controller;
use think\Controller;

class Picture extends Base{

    /**
     * 人数统计
     * @return mixed
     */
    public function index(){
        $condition['status'] = [
            'neq',config('code.status_delete')
        ];
        $res = model('Member')->where($condition)
            ->field(['id','name','last_login_time'])
            ->limit(0,5)
            ->select();

        $data[0] = 0;
        $data[1] = 0;
        $data[2] = 0;
        $data[3] = 0;
        $data[4] = 0;
        $data[5] = 0;
        $data[6] = 0;
        $data[7] = 0;
        foreach ($res as $v){
            $hour = substr($v['last_login_time'],11,2);
            $hour = intval($hour);
            if($hour>=22&&$hour<24){
                $data[6] += 1;
            }if($hour>=20&&$hour<22){
                $data[5] += 1;
            }if($hour>=18&&$hour<20){
                $data[4] += 1;
            }if($hour>=15&&$hour<18){
                $data[3] += 1;
            }if($hour>=12&&$hour<15){
                $data[2] += 1;
            }if($hour>=9&&$hour<12){
                $data[1] += 1;
            }if($hour>=6&&$hour<9){
                $data[0] += 1;
            }
        }
       // var_dump($data);
       // for ($i=0;$i<=7;$i++){
       //     $data[$i] = strval($data[$i]);
       // }
       // var_dump($data);
        return $this->fetch('',[
            'res' => $res,
            'data' => $data,
        ]);
    }

    /**
     * 文章统计
     * @return mixed
     */
    public function coutn(){
        $condition['status'] = [
            'neq',config('code.status_delete')
        ];
        $order = ['read_count' => 'desc'];
        $res = model('News')->where($condition)
            ->field(['id','title','read_count'])
            ->limit(0,5)
            ->order($order)
            ->select();
        $sum = 0;
        foreach ($res as $v){
            $sum += $v['read_count'];
        }
        $res[0]['sum'] = $sum;
        return $this->fetch('',[
            'res' => $res,
        ]);
    }
}