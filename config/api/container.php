<?php

use App\Twig\AssetExtension;
use App\Twig\CsrfExtension;
use Awurth\SlimValidation\Validator;
use Awurth\SlimValidation\ValidatorExtension;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Cartalyst\Sentinel\Native\SentinelBootstrapper;
use Illuminate\Database\Capsule\Manager;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Slim\Csrf\Guard;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Twig\Extension\DebugExtension;
use Interop\Container\ContainerInterface;

$config = C();
$capsule = new Manager();
$capsule->addConnection($config['settings']['eloquent']);
$capsule->setAsGlobal();
$capsule->bootEloquent();



$container['secret-key'] = 'sa9328343nd774788dhdhd-884747jjj99387jjhd-09';
$container["jwt"] = function ($container) {
    return new StdClass;
};

$container['auth'] = function (ContainerInterface $c) {
    $sentinel = new Sentinel(new SentinelBootstrapper($c->get('settings')['sentinel']));
    return $sentinel->getSentinel();
};

$container['flash'] = function () {
    return new Messages();
};

$container['csrf'] = function (ContainerInterface $c) {
    $guard = new Guard();
    $guard->setFailureCallable($c->get('csrfFailureHandler'));

    return $guard;
};

// https://github.com/awurth/SlimValidation
$container['validator'] = function () {
    return new Validator();
};


$container['monolog'] = function (ContainerInterface $c) {
    $config = $c->get('settings')['monolog'];

    $logger = new Logger($config['name']);
    $logger->pushProcessor(new UidProcessor());
    $logger->pushHandler(new StreamHandler($config['path'], $config['level']));

    return $logger;
};
$container[\Slim\Views\Twig::class] = function (ContainerInterface $c) {


    $config = $c->get('settings')['twig'];



    $twig = new Twig($config['path'], $config['options']);

    $twig->addExtension(new TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $twig->addExtension(new DebugExtension());
    $twig->addExtension(new CsrfExtension($c->get('csrf')));
    $twig->addExtension(new ValidatorExtension($c->get('validator')));
    $twig->addExtension(new AssetExtension($c->get('request')));

    $twig->getEnvironment()->addGlobal('flash', $c->get('flash'));
    $twig->getEnvironment()->addGlobal('auth', $c->get('auth'));

    return $twig;
};

return $container;