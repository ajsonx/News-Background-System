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
    protected $autoWriteTimestamp=false;

    /**
     * 新增
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function add($data){
        if (!is_array($data)) {
            exception('传递数据不合法');
        }
        //allowField 数据的字段在表当中不存在则报错
        $this->allowField(true)->save($data);

        return $this->id;
    }

    /**
     * 根据条件获取列表数据(带分页)
     * @param array $condition
     * @param int $from
     * @param int $size
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDataByCondition($condition =[],$from = 0,$size = 5){

        $order = ['id' => 'asc'];

        $result = $this->where($condition)
            ->limit($from, $size)
            ->order($order)
            ->select();

        return $result;
    }

    /**
     * 返回分页总数 感觉有点重复了,不知道有没有方法把他们写到一起
     * @param array $condition
     * @return int|string
     */
    public function getDataCountByCondition($condition = []){

        $result = $this->where($condition)
            ->count();

        return $result;
    }

    /**
     * @param array $userIds
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUsersUserId($userIds = []) {
        $data = [
            'id' => ['in', implode(',', $userIds)],// in
            'status' => 1,
        ];

        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)
            ->field(['id', 'name', 'image'])
            ->order($order)
            ->select();
    }

}
