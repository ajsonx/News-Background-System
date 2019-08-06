<?php
/**
 * 点赞接口
 */
namespace app\api\controller\v1;

class Upvote extends AuthBase {

    /**
     * 新闻点赞功能开发
     * @return array
     */
    public function save() {
        $id = input('post.id', 0 , 'intval');
        if(empty($id)) {
            return apiShow(config('code.error'), 'id不存在', [], 404);
        }
        // 判定这个 id的新闻文章  是否存在
        $data = [
            'user_id' => $this->userMsg->id,
            'news_id' => $id,
        ];
        // 查询库里面是否存在 点赞
        $userNews = model('UserNews')->get($data);
        if($userNews) {
            return apiShow(config('code.error'), '已经点赞过，不能再次点赞', [], 401);
        }

        try {
            $userNewsId = model('UserNews')->add($data);
            if($userNewsId) {
                model('News')->where(['id' => $id])->setInc('upvote_count');
                return apiShow(config('code.success'), 'OK', [], 200);
            }else {
                return apiShow(config('code.error'), '内部错误 点赞失败', [], 500);
            }
        }catch (\Exception $e) {
            return apiShow(config('code.error'), '内部错误 点赞失败', [], 500);
        }

    }

    /**
     * 取消点赞
     */
    public function delete() {
        $id = input('delete.id', 0, 'intval');
        if(empty($id)) {
            return apiShow(config('code.error'), 'id不存在', [], 404);
        }

        // 判定这个 id的新闻文章 是否存在
        $data = [
            'user_id' => $this->userMsg->id,
            'news_id' => $id,
        ];
        // 查询库里面是否存在 点赞
        $userNews = model('UserNews')->get($data);
        if(empty($userNews)) {
            return apiShow(config('code.error'), '没有被点赞过，无法取消', [], 401);
        }

        try {
            $userNewsId = model('UserNews')
                ->where($data)
                ->delete();
            if($userNewsId) {
                model('News')->where(['id' => $id])->setDec('upvote_count');
                return apiShow(config('code.success'), 'OK', [], 200);
            }else {
                return apiShow(config('code.error'), '取消失败', [], 500);
            }
        }catch (\Exception $e) {
            return apiShow(config('code.error'), '内部错误 点赞失败', [], 500);
        }
    }

    /**
     * 查看文章是否被该用户点赞
     */
    public function read() {
        $id = input('param.id', 0, 'intval'); // id=1,2,4,5,6,7,78
        if(empty($id)) {
            return apiShow(config('code.error'), 'id不存在', [], 404);
        }

        // 判定这个 id的新闻文章  -> ent_news 小伙伴自行加入
        // 查询库里面是否存在 点赞

        $data = [
            'user_id' => $this->userMsg->id,
            'news_id' => $id,
        ];

        $userNews = model('UserNews')->get($data);
        if($userNews) {
            return apiShow(config('code.success'), 'OK', ['isUpVote' => 1], 200);
        }else {
            return apiShow(config('code.success'), 'OK', ['isUpVote' => 0], 200);
        }

    }
}