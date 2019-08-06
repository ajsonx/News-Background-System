<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/22
 * Time: 21:20
 */
namespace app\common\validate;

use think\Validate;

class Code extends Validate{

    protected $rule = [
        'id' => 'require|number|length:11',
    ];
}