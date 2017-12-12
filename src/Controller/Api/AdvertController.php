<?php

namespace App\Controller\Api;

use App\Helpers\CoinHelpers;
use App\Model\User;
use App\Repositories\AdvertRepository;
use App\Service\AdvertService;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Firebase\JWT\JWT;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;

class AdvertController extends Controller
{




    public function overview(Request $request,Response $response)
    {
        $adverts = AdvertService::search($request,20);
        return $this->json($response,$adverts);
    }



    public function show(Request $request,Response $response,$args)
    {
        $advert = AdvertService::get($args['id']);

        return $this->json($response,$advert);
    }


    public function getByUser(Request $request,Response $response){

        $adverts= AdvertService::getByUser($request,$this->user);
        $coins=CoinHelpers::getIds();
        foreach ($adverts as $k=>$v){
            $adverts[$k]->coin_name=$coins[$v->coin_type]['name'];
        }

        return  $this->json($response,$adverts);

    }



}
