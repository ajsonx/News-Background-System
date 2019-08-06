<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/12/7
 * Time: 10:47
 */
namespace app\common\model;


use think\Db;

class Comment extends Base {

    /**
     * 通过条件获取评论的数量
     * @param array $param
     * @return int|string
     */
    public function getNormalCommentsCountByCondition($param = []) {
        // status =  1 小伙伴自行完成
        $count = Db::table('Comment')
            ->alias(['Comment' => 'a', 'Member' => 'b'])
            ->join('Member', 'a.user_id = b.id AND a.news_id = ' . $param['news_id'])
            ->count();
        return $count;
    }

    /**
     * 通过条件获取列表数据
     * @param array $param
     * @param int $from
     * @param int $size
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getNormalCommnetsByCondition($param = [], $from=0, $size = 5) {
        $result = Db::table('Comment')
            ->alias(['Comment' => 'a', 'Member' => 'b'])
            ->join('Member', 'a.user_id = b.id AND a.news_id = ' . $param['news_id'])
            ->limit($from, $size)
            ->order(['a.id' => 'desc'])
            ->select();
        return $result;
    }

    /**
     * 获取总数量
     * @param array $param
     * @return int|string
     */
    public function getCountByCondition($param = []) {
        return $this->where($param)
            ->field('id')
            ->count();
    }

    /**
     * 评论按顺序加载
     * @param array $param
     * @param int $from
     * @param int $size
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getListsByCondition($param = [], $from=0, $size = 5) {
        return $this->where($param)
            ->field('*')
            ->limit($from, $size)
            ->order(['id' => 'desc'])
            ->select();
    }
}
