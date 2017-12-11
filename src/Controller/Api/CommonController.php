<?php

namespace App\Controller\Api;

use App\Model\User;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Firebase\JWT\JWT;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;

class CommonController extends Controller
{



    public function captcha(Request $request, Response $response)
    {
         return $this->json($response,'发送成功');
    }


    public function captchaReg(Request $request, Response $response){

        return $this->json($response,'发送成功');
    }
}
