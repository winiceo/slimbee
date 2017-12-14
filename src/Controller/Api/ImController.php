<?php

namespace App\Controller\Api;



use App\Leven;
use App\Model\Chat;
use App\Service\ChatService;
use Carbon\Carbon;
use Leven\Auth;
use Leven\Log;
use Slim\Http\Request;

class ImController extends Controller
{

    public function send(Request $request)
    {
        $params = $request->getParams();
        $message=ChatService::getMessage();
        $message=(object) array_merge((array) $message,(array) $params );

        $ret=ChatService::store($message);

        return $this->setMessage('')->setData($message)->success();

    }

    public function history(Request $request, Chat $chat){

        $messages=$chat->where(function ($query) use ($request) {
            $order_id = $request->getParam('order_id', 0);
            $query->where('order_id', $order_id);
        })->orderBy('id', 'desc')
            ->paginate(20);

        return $this->setMessage('')->setData($messages)->success();

    }

    public function upload(){



    }

}
