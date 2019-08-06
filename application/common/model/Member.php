<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/9/22
 * Time: 13:19
 */
namespace app\common\model;


class Member extends Base
{
    /**
     * 获取用户信息
     * @param array $data
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getMember($data = [])
    {
        $data['status'] = [
            'neq', config('code.status_delete')
        ];
        $order = ['id' => 'asc'];

        $result = $this->where($data)
            ->order($order)
            ->paginate();

        return $result;
    }

}
