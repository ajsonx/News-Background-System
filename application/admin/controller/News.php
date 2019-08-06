<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/9/1
 * Time: 16:51
 */

namespace app\admin\controller;

/**
 * 新闻内容的增删改查
 * Class News
 */
class News extends Base
{
    /**
     * 新闻显示
     * 1）查询
     * 2）分页
     * @return mixed
     */
    public function index()
    {
        $this->model = 'news';
        //用tp自带获取分页方法
        //$news = model('news')->getNews();

        //模式二 传统分页 page size ==》sql语句 limit
        $data = input('param.');

        $query = http_build_query($data);
        //halt($data);
        //传递分页信息
        $whereData = [];
        $this->getPageSize($data);
        //定义当前数据库表名
        /**
         * 将获取分页的写到基础类里 代码复用
         * $whereData['page'] = $this->page
         */

        //判断查询条件，构造条件数组  开始日期到结束日期
        if (!empty($data['start_time']) && !empty($data['end_time']) && $data['start_time'] < $data['end_time']) {
            $whereData['update_time'] = [
                ['gt', strtotime($data['start_time'])],
                ['lt', strtotime($data['end_time'])],
            ];
        }
        //根据栏目id查询
        if (!empty($data['catid'])) {
            //int intval(mixed var, int [base]);本函数可将变量转成整数类型。
            //可省略的参数 base 是转换的基底，默认值为 10。转换的变量 var 可以为数组或类之外的任何类型变量。
            $whereData['catid'] = intval($data['catid']);
        }
        //根据标题查询

        if (!empty($data['title'])) {
            if (trim($data['title']) !== "") {
                $whereData['title'] = [
                    'like', '%' . $data['title'] . '%'    //模糊查询
                ];
            }
        }
        //获取表里的数据显示到页面，需要在模型里建立方法
        $news = model('News')->getDataByCondition($whereData, $this->from, $this->size);
        //获取满足条件的数据总数 有多少页
        $total = model('News')->getDataCountByCondition($whereData);

        foreach ($news as $k) {
            if (empty($k['image'])) {
                $k['image'] = 'https://goer-app.oss-cn-qingdao.aliyuncs.com/nophoto.jpg';
            }
        }

        $pageTotal = ceil($total / $this->size);

        return $this->fetch(
            '', [
                'cats' => config('cat.lists'),
                'news' => $news,
                'pageTotal' => $pageTotal,
                'curr' => $this->page,
                'start_time' => !empty($data['start_time']) ? $data['start_time'] : '',
                'end_time' => !empty($data['end_time']) ? $data['end_time'] : '',
                'title' => !empty($data['title']) ? $data['title'] : '',
                'catid' => !empty($data['catid']) ? $data['catid'] : 0,
                'query' => $query,
            ]
        );

    }

    /**
     * 新闻内容添加
     * @return mixed
     */
    public function add()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $data['create_time'] = time();
            $data['update_time'] = time();
            try {
                $id = model('News')->add($data);
            } catch (\Exception $e) {
                echo $e->getMessage();
                return $this->result('', 0, '添加新闻失败');
            }
            //如果有返回，抛送url跳转到首页
            if ($id) {
                return $this->result([
                    'jump_url' => url('news/index')
                ], 1, '添加成功');
            } else {

                return $this->result('', 0, '添加新闻失败');
            }
        }
        else {
            return $this->fetch('', ['cats' => config('cat.lists')]);
        }
    }

    /**
     * 新闻内容查看
     * @return mixed
     */
    public function show()
    {
        $whereData['id'] =
            intval(input('get.id'));
        $whereData['status'] = [
            'neq', config('code.status_delete')
        ];
        $news = model('News')
            ->where($whereData)
            ->order(['id' => 'desc'])
            ->select();
        foreach ($news as $k) {
            if (empty($k['image'])) {
                $k['image'] = 'https://goer-app.oss-cn-qingdao.aliyuncs.com/nophoto.jpg';
            }
        }
        return $this->fetch('',
            [
                'news' => $news
            ]);
    }

    /**
     * 新闻内容编辑
     * @return mixed
     */
    public function edit()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $data['update_time'] = time();

            try {
                $id = model('News')->save($data, ['id' => $data['id']]);
            } catch (\Exception $e) {
                echo $e->getMessage();
                return $this->result('', 0, '编辑新闻失败');
            }
            //如果有返回，抛送url跳转到首页
            if ($id) {
                return $this->result([
                    'jump_url' => url('news/index')
                ], 1, '添加成功');
            } else {
                return $this->result('', 0, '编辑新闻失败');//可以放到配置文件里
            }
        } else if (request()->isGet()) {
            $whereData['id'] =
                intval(input('get.id'));
            try {
                $news = model('News')->getTotalNews($whereData);
            } catch (\Exception $e) {
                return $this->result('', 0, '查看新闻失败');
            }

            $cats = config('cat.lists');
            return $this->fetch('',
                [
                    'news' => $news,
                    'cats' => $cats,
                ]);
        }
    }

    /**
     * 新闻推送
     * @param string $type
     * @return string
     */
    public function jpush($type = 'android')
    {
        $data = input('param.');
        try{
            $res = model($data['mod'])
                ->where(['id' => $data['id']])
                ->field(['id', 'title','description'])
                ->select();

        }catch (\Exception $e){
        }
        /*

         $pusher->setNotificationAlert('Hello, JPush');
         try {
             $pusher->send();
         } catch (\JPush\Exceptions\JPushException $e) {
             // try something else here
             print $e;
         }*/
        try {
            $client = new \JPush\Client("fc137365c114f61b67d1e6c7", "a621dfadfce281629b254770");
            $pusher = $client->push();
            $pusher->setPlatform('all');
            $pusher->addAllAudience();
            $pusher->setNotificationAlert($res[0]->title);
            $pusher->androidNotification($res[0]->title, array(
                    'title' => $res[0]->title,
                    'extras' => array(
                        'newsId' => $data['id']
                    ),
                ))->send();
        } catch (\Exception $e) {
            echo $e->getMessage();
            return json_encode(['status'=> 0]);
        }
        return $this->result([
            'jump_url' => url('news/index')
        ], 1, '推送成功');
    }
}
