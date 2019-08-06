<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/4/22
 * Time: 12:46
 */

namespace app\common\model;


class News extends Base
{

    /**
     * 获取带条件的所有新闻
     * @param array $condition
     * @return mixed
     */
    public function getTotalNews($condition = [])
    {
        $order = ['id' => 'desc'];

        $news = model('News')
            ->where($condition)
            ->order($order)
            ->select();

        return $news;
    }

    /**
     * 获取新闻分页内容
     * @param array $condition
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getNews($condition = [])
    {

        if (!isset($condition['status'])) {
            $condition['status'] = [
                'neq', config('code.status_delete')
            ];
        }

        $order = ['id' => 'desc'];

        $result = $this->where($condition)
            ->order($order)
            ->paginate();

        return $result;
    }

    /**
     * 获取列表数据
     * @param array $condition
     * @param int $from
     * @param int $size
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDataByCondition($condition = [], $from = 0, $size = 5)
    {

        $order = ['id' => 'desc'];

        $result = $this->where($condition)
            ->field($this->_getField())
            ->limit($from, $size)
            ->order($order)
            ->select();

        return $result;
    }

    /**
     * 获取头图推荐新闻数据
     * @param int $num
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getIndexHeadNormalNews($num = 5)
    {
        $data = [
            'status' => 1,
            'is_head_figure' => 1,
        ];

        $order = [
            'id' => 'desc',
        ];

        $result = $this->where($data)
            ->field($this->_getField())
            ->order($order)
            ->limit($num)
            ->select();

        return $result;
    }

    /**
     * 获取首页推荐新闻列表数据
     * @param int $num
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getIndexPositionNews($num = 20)
    {

        $data = [
            'status' => 1,
            'is_position' => 1,
        ];

        $order = [
            'id' => 'desc',
        ];

        $resutl = $this->where($data)
            ->field($this->_getField())
            ->order($order)
            ->limit($num)
            ->select();

        return $resutl;
    }

    /**
     * 筛选从数据库中查找到的信息匹配显示到缩略图中(为了排除内容较多的contens)
     * @return array
     */
    private function _getField()
    {
        return [
            'id',
            'catid',
            'image',
            'title',
            'read_count',
            'is_position',
            'status',
            'update_time',
            'create_time',
        ];
    }

    /**
     * 获取新闻阅读量排名数据
     * @param int $num 推荐数
     * @param int $hobby 某用户兴趣爱好
     * @param array $percent 某用户浏览新闻分类百分比
     * @return array|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getNewsRank($num = 7, $hobby = 0,$percent = [])
    {
        if (!isset($condition['status'])) {
            $condition['status'] = [
                'neq', config('code.status_delete')
            ];
        }
        $result = [];
        $order = ['read_count' => 'desc'];
        if(!empty($percent)){
            if ($percent['maxcount'] > config('code.percent')){
                $hobby = $percent['catid'];
            }
        }
        if ($hobby != 0) {
            $condition['catid'] = [
                'eq', $hobby
            ];
            $result =  $this->where($condition)
                ->field($this->_getField())
                ->order($order)
                ->limit($num)
                ->select();
        }

        if(empty($result) || count($result) < config('code.page')) {
            $con['catid'] = [ 'neq', $hobby ];
            $con['status'] = ['neq', config('code.status_delete')];
            $result += $this->where($con)
                ->field($this->_getField())
                ->order($order)
                ->limit(config('code.page'))
                ->select();
        }
        return $result;
    }

    /**
     * 根据来获取列表的数据
     * @param array $condition
     * @param int $from
     * @param int $size
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getNewsByCondition($condition = [], $from = 0, $size = 5)
    {
        if (!isset($condition['status'])) {
            $condition['status'] = [
                'neq', config('code.status_delete')
            ];
        }

        $order = ['id' => 'desc'];

        $result = $this->where($condition)
            ->field($this->_getField())
            ->limit($from, $size)
            ->order($order)
            ->select();

        return $result;
    }

    /**
     * 根据条件来获取列表的数据的总数
     * @param array $condition
     * @return int|string
     */
    public function getNewsCountByCondition($condition = [])
    {
        if (!isset($condition['status'])) {
            $condition['status'] = [
                'neq', config('code.status_delete')
            ];
        }

        $result = $this->where($condition)
            ->count();

        return $result;
    }
}
