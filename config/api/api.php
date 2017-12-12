<?php

use DI\Annotation\Inject;
use Slim\Views\Twig;
class UserController
{
    /**
     * Annotation combined with phpdoc:
     *
     * @Inject
     * @var Twig
     */
    protected $twig;
    /**
     * @Inject
     * @var \App\Model\User
     */
    protected $user;
    public function delete($request, $response)
    {


        $user=$this->user->findOrfail(3);
        dump($user);

         return $this->twig->render($response, 'home.twig');
    }
}


$app->get('/', function($request, $response){
    return $response->withRedirect('/apidoc/','delete');

});

$this->map(['GET', 'POST'],'/api/cc', ['api.test','delete'])  ;

$app->get('/bootstrappers', function($request, $response){
    $data=[
        'app_name'=>'beeotc',
    ];
    return $response->withJson($data);

});



$app->group('/api/v2', function () use($container) {
    $this->group('/test', function () {


        $this->map(['GET', 'POST'], '/ok/{id}', ['api.test', 'ok']);
    });
    $this->get('/bootstrappers', function($request, $response){
        $data=[
            'app_name'=>'beeotc',
        ];
        return $response->withJson($data);

    });

    $this->post('/user/login', ['api.user','login']) ;
    $this->post('/user/register', ['api.user','register']) ;
    $this->group('/user', function () {
        $this->post('/profile', ['api.user','profile']) ;
        $this->post('/logout', ['api.user','logout']) ;
        $this->post('/safe/check', ['api.user','check']) ;

        $this->post('/advert', ['api.advert','getByUser']) ;
    })->add($container->get('jwt.middleware')) ;

    $this->group('/finance', function () {
        $this->post('/depoist', ['api.user','depoist']) ;
        $this->post('/withdraw', ['api.user','withdraw']) ;
    })->add($container->get('jwt.middleware')) ;


    $this->group('/wallet', function () {
        $this->post('/address', ['api.wallet','address']) ;
        $this->post('/address/store', ['api.wallet','storeAddress']) ;
        $this->post('/deposit/{id}', ['api.wallet','deposit']) ;


        $this->post('/withdraw', ['api.wallet','withdraw']) ;
        $this->post('/withdraw/history', ['api.wallet','history']) ;


    })->add($container->get('jwt.middleware')) ;




    $this->group('/advert', function () {
        $this->post('', ['api.advert','overview']) ;
        $this->post('/{id}', ['api.advert','show']) ;
    });


    $this->group('/verifycodes', function () {
        $this->post('', ['api.common','captcha']) ;
        $this->post('/register', ['api.common','captchaReg']) ;
    });

    $this->group('/order', function () {

        $this->post('/order/create', ['api.order','store']) ;
        $this->post('/order/pay', ['api.order','pay']) ;
        $this->post('/order/cancel', ['api.order','cancel']) ;
        $this->post('/order/release', ['api.order','release']) ;
        $this->post('/order/comment', ['api.order','comment']) ;
        $this->post('/order/complaint', ['api.order','complaint']) ;

    })->add($container->get('jwt.middleware')) ;




}) ;


$app->group('/chat', function () {
    $this->post('/message/send', 'api.chat','messageSend') ;
    $this->post('/message/history', 'api.chat','history') ;
    $this->post('/upload', 'api.chat','upload');
}) ;






