<?php
//首页暂时跳转
//文档
$app->get('/', function($request, $response){
    return $response->withRedirect('/apidoc/',301);

});

$this->map(['GET', 'POST'],'/ok', 'api.test.controller:ok')  ;

$app->get('/bootstrappers', function($request, $response){
    $data=[
        'app_name'=>'beeotc',
    ];
    return $response->withJson($data);

});



$app->group('/api/v2', function () use($container) {
    $this->map(['GET', 'POST'],'/test', 'api.test.controller:test')  ;
    $this->get('/bootstrappers', function($request, $response){
        $data=[
            'app_name'=>'beeotc',
        ];
        return $response->withJson($data);

    });

    $this->post('/user/login', 'api.user.controller:login') ;
    $this->post('/user/register', 'api.user.controller:register') ;
    $this->group('/user', function () {
        $this->post('/profile', 'api.user.controller:profile') ;
        $this->post('/logout', 'api.user.controller:logout') ;
        $this->post('/safe/check', 'api.user.controller:check') ;

        $this->post('/advert', 'api.advert.controller:getByUser') ;
    })->add($container['jwt.middleware']) ;

    $this->group('/finance', function () {
        $this->post('/depoist', 'api.user.controller:depoist') ;
        $this->post('/withdraw', 'api.user.controller:withdraw') ;
    })->add($container['jwt.middleware']) ;


    $this->group('/wallet', function () {
        $this->post('/address', 'api.wallet.controller:address') ;
        $this->post('/address/store', 'api.wallet.controller:storeAddress') ;
        $this->post('/deposit/{id}', 'api.wallet.controller:deposit') ;


        $this->post('/withdraw', 'api.wallet.controller:withdraw') ;
        $this->post('/withdraw/history', 'api.wallet.controller:history') ;


    })->add($container['jwt.middleware']) ;




    $this->group('/advert', function () {
        $this->post('', 'api.advert.controller:overview') ;
        $this->post('/{id}', 'api.advert.controller:show') ;
    });


    $this->group('/verifycodes', function () {
        $this->post('', 'api.common.controller:captcha') ;
        $this->post('/register', 'api.common.controller:captchaReg') ;
    });

    $this->group('/order', function () {

        $this->post('/order/create', 'api.order.controller:store') ;
        $this->post('/order/pay', 'api.order.controller:pay') ;
        $this->post('/order/cancel', 'api.order.controller:cancel') ;
        $this->post('/order/release', 'api.order.controller:release') ;
        $this->post('/order/comment', 'api.order.controller:comment') ;
        $this->post('/order/complaint', 'api.order.controller:complaint') ;

    }) ->add($container['jwt.middleware']) ;




}) ;


$app->group('/chat', function () {
    $this->post('/message/send', 'api.chat.controller:messageSend') ;
    $this->post('/message/history', 'api.chat.controller:history') ;
    $this->post('/upload', 'api.chat.controller:upload');
}) ;











