<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2019/1/1
 * Time: 15:30
 */
namespace app\common\model;

class UserView extends Base{

    /**
     * 获取浏览
     * @param $id
     * @return string
     */
    public function getViewCount($id){
        $nbaCount = $this->where(['cat_id' => '1','user_id' => $id])
            ->count();
        $naBallCount = $this->where(['cat_id' => '2','user_id' => $id])
            ->count();
        $cbaCount = $this->where(['cat_id' => '3','user_id' => $id])
            ->count();
        $cnBallCount = $this->where(['cat_id' => '4','user_id' => $id])
            ->count();
        $synCount = $this->where(['cat_id' => '5','user_id' => $id])
            ->count();
        $str = $nbaCount.$naBallCount.$cbaCount.$cnBallCount.$synCount."";
        return $str;
    }

    /**
     * 获取浏览量值最大
     * @param $id
     * @return mixed
     */
    public function getMaxViewCount($id){
        $catid = '12345';
        $nbaCount = $this->where(['cat_id' => '1','user_id' => $id])
            ->count();
        $naBallCount = $this->where(['cat_id' => '2','user_id' => $id])
            ->count();
        $cbaCount = $this->where(['cat_id' => '3','user_id' => $id])
            ->count();
        $cnBallCount = $this->where(['cat_id' => '4','user_id' => $id])
            ->count();
        $synCount = $this->where(['cat_id' => '5','user_id' => $id])
            ->count();
        $maxid = max($nbaCount,$naBallCount,$cbaCount,$cnBallCount,$synCount);

        if($nbaCount == $maxid){
            return [
                'catid' =>  $catid[0],
                'maxcount' => $maxid,
            ];
        }
        else if($naBallCount == $maxid){
            return [
                'catid' =>  $catid[1],
                'maxcount' => $maxid,
            ];
        }
        else if($cbaCount == $maxid){
            return [
                'catid' =>  $catid[2],
                'maxcount' => $maxid,
            ];
        }
        else if($cnBallCount == $maxid){
            return [
                'catid' =>  $catid[3],
                'maxcount' => $maxid,
            ];
        }
        else if($synCount == $maxid){
            return [
                'catid' =>  $catid[4],
                'maxcount' => $maxid,
            ];
        }
    }
}
