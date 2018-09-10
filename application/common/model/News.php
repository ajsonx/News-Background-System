<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/4/22
 * Time: 12:46
 */
namespace app\common\model;


class News extends Base {
    public function getNews($data = []){
        $data['status'] = [
            'neq',config('code.status_delete')
        ];

        $order = ['id' => 'desc'];


        $result = $this->where($data)
            ->order($order)
            ->paginate();

        // 调试
        echo $this->getLastSql();

        return $result;
    }
}