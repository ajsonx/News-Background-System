<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/9/17
 * Time: 10:07
 */
namespace app\common\lib\exception;

use think\exception\Handle;

class ApiHandleException extends Handle {
    public $httpCode = 500;
    /**
     * Render an exception into an HTTP response.
     * 报错信息渲染
     * @param  \Exception $e
     * @return Response
     */
    public function render(\Exception $e)
    {
        if($e instanceof ApiException){
            $this->httpCode = $e->httpCode;
        }
        echo $e->getMessage();
        return apiShow(0,'非法请求',$data = [],$this->httpCode);
    }
}