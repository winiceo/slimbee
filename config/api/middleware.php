<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use Slim\Middleware\JwtAuthentication;

use Interop\Container\ContainerInterface;


$container->set('guest.middleware',function (ContainerInterface $container) {
    return new GuestMiddleware($container->get('router'), $container->get('auth'));
});

$container->set('auth.middleware',function ( ContainerInterface $container) {
    return function ($role = null) use ($container) {
        return new AuthMiddleware($container->get('router'), $container->get('falsh'), $container->get('auth'), $role);
    };
});

$container->set('jwt.middleware',  function (ContainerInterface $container) {

    return new JwtAuthentication([
        "secret" => $container->get('secret-key'),
        "secure" => false,
        "path" => "/api/v2",
        "algorithm" => ["HS256"],
        "passthrough" => ["/api/v2/user/login"],
        "attribute" => "jwt",
        "callback" => function ($request, $response, $arguments)use($container)   {

            $container->set('jwt',$arguments["decoded"]);

        }
    ]);

});
//$app->add($container['csrf']);
return $container;

