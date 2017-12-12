<?php


$definitions = [
    'settings.responseChunkSize' => 4096,
    'settings.outputBuffering' => 'append',
    'settings.determineRouteBeforeAppMiddleware' => false,
    'settings.displayErrorDetails' => false,
];

$definitions[\Slim\Views\Twig::class] = function (\Psr\Container\ContainerInterface $c)
{
    $twig = new \Slim\Views\Twig($c->get('templates'), [
        'cache' => $c->get('cache')."/twig/"
    ]);

    $twig->addExtension(new \Slim\Views\TwigExtension(
        $c->get('router'),
        $c->get('request')->getUri()
    ));

    return $twig;
};


