<?php
/**
 * Created by PhpStorm.
 * User: genv
 * Email:leven.zsh@gmail.com
 * Date: 2017/12/11
 * Time: 下午6:02
 */


$app->group('', function () {
    $this->map(['GET', 'POST'], '/login', 'auth.controller:login')->setName('login');
    $this->map(['GET', 'POST'], '/register', 'auth.controller:register')->setName('register');
})->add($container['guest.middleware']);

$app->get('/logout', 'auth.controller:logout')
    ->add($container['auth.middleware']())
    ->setName('logout');

$app->group('/admin', function () {
    $this->get('', 'admin.controller:home')->setName('admin.home');
})->add($container['auth.middleware']('admin'));
