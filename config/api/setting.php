<?php
/**
 * Created by PhpStorm.
 * User: genv
 * Date: 2017/12/12
 * Time: 下午2:14
 */

return [
    'settings' => [
        'displayErrorDetails' => false,
        'ibnu' => 'syuhada'
    ],


    'Illuminate\Database\Capsule\Manager' => function (ContainerInterface $c) {

        $capsule = new \Illuminate\Database\Capsule\Manager;
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'test',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        return $capsule;
    }
];