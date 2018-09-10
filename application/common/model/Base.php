<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/4/22
 * Time: 12:46
 */
namespace app\common\model;

use think\Model;

class Base extends Model{
    protected $autoWriteTimestamp=true;

    /**
     * 新增
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function add($data){
        if(!is_array($data)){
            exception('传递数据不合法');
        }
        //allowField 数据的字段在表当中不存在则报错
        $this->allowField(true)->save($data);
        return $this->id;
    }
}