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
    public function delete($request, $response,\App\Model\User $user)
    {

        $user=$user->findOrfail(3);

         return $this->twig->render($response, 'home.twig');
    }
}


$app->group('/api/v2', function () use($container) {
    $this->map(['GET', 'POST'], '/test', ['api.test.controller','ok']);
    $this->get('/bootstrappers', function ($request, $response) {
        $data = [
            'app_name' => 'beeotc',
        ];
        return $response->withJson($data);

    });
});
//use  Slim\Views\Twig;
  //$app->get('/api/{id}', ['UserController', 'delete']);
  $app->get('/api/{id}', ['UserController', 'delete']);
//
//$app->get('/api/test', function ($response, Twig $twig,) {
//    echo 444;
//
//    $user=$user->findOrfail(3);
//    dump($user);
//    return $twig->render($response, 'home.twig');
//});



