<?php

namespace App\Controller\Api;

use App\Model\User;
use App\Repositories\AdvertRepository;
use App\Service\AdvertService;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Firebase\JWT\JWT;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;

class TestController extends Controller
{


    public function test(Request $request,Response $response)
    {
echo 4444;
    }

}
