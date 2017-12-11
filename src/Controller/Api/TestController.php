<?php

namespace App\Controller\Api;

use App\Model\User;
use App\Model\WalletAddress;
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
         for($i=0;$i<1000;$i++){
             $data['coin_type']=2;
             $data['address']=uniqid().generate_code();

             WalletAddress::create($data);
         }
    }

}
