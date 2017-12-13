<?php
/**
 * Created by PhpStorm.
 * User: genv
 * Date: 2017/12/13
 * Time: 上午11:48
 */

namespace App;



class Leven
{

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;



    }
    /**
     * @return string
     * 生成订单号
     */
    public static function uuid()
    {
        /* 选择一个随机的方案 */
        mt_srand((double)microtime() * 1000000);
        return date('Ymdhis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }
//生成验证码
    public function generate_code($length = 6) {
        return rand(pow(10,($length-1)), pow(10,$length)-1);
    }



}