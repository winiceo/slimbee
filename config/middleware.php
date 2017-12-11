<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use Slim\Middleware\JwtAuthentication;

$container['guest.middleware'] = function ($container) {
    return new GuestMiddleware($container['router'], $container['auth']);
};

$container['auth.middleware'] = function ($container) {
    return function ($role = null) use ($container) {
        return new AuthMiddleware($container['router'], $container['flash'], $container['auth'], $role);
    };
};

$container['jwt.middleware'] = function ($container) {

    return new JwtAuthentication([
        "secret" => $container->get('secret-key'),
        "secure" => false,
        "path" => "/api/v2",
        "algorithm" => ["HS256"],
        "passthrough" => ["/api/v2/user/login"],
        "attribute" => "jwt",
        "callback" => function ($request, $response, $arguments)use($container)   {

            $container["jwt"] = $arguments["decoded"];

        }
    ]);

};

//$app->add($container['csrf']);

