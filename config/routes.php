<?php
//首页暂时跳转

$app->get('/', function($request, $response){

    return $response->withRedirect('/docs/', 301);

});



$app->group('/api/v2', function () use($container) {
    $this->get('/test', 'api.test.controller:test') ;

    $this->post('/user/login', 'api.user.controller:login') ;
    $this->post('/user/register', 'api.user.controller:register') ;
    $this->group('/user', function () {
        $this->post('/profile', 'api.user.controller:profile') ;
        $this->post('/logout', 'api.user.controller:logout') ;
        $this->post('/safe/check', 'api.user.controller:check') ;
        $this->get('/address', 'api.user.controller:address');
        $this->get('/adverts', 'api.advert.controller:getByUser') ;
    })->add($container['jwt.middleware']) ;

    $this->group('/finance', function () {
        $this->post('/depoist', 'api.user.controller:depoist') ;
        $this->post('/withdraw', 'api.user.controller:withdraw') ;

    }) ;

    $this->group('/adverts', function () {
        $this->post('', 'api.advert.controller:overview') ;
        $this->post('/{id}', 'api.advert.controller:show') ;
    });

}) ;


$app->group('/chat', function () {
    $this->post('/message/send', 'api.chat.controller:messageSend') ;
    $this->post('/message/history', 'api.chat.controller:history') ;
    $this->post('/upload', 'api.chat.controller:upload');
}) ;

$app->group('/order', function () {

    $this->post('/order/create', 'api.order.controller:create') ;
    $this->post('/order/pay', 'api.order.controller:pay') ;
    $this->post('/order/cancel', 'api.order.controller:cancel') ;
    $this->post('/order/release', 'api.order.controller:release') ;
    $this->post('/order/comment', 'api.order.controller:comment') ;
    $this->post('/order/complaint', 'api.order.controller:complaint') ;


}) ;









