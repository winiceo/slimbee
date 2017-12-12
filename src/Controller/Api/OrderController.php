<?php
/**
 * Created by PhpStorm.
 * User: genv
 * Date: 2017/12/12
 * Time: 上午10:19
 */

namespace App\Controller\Api;


use App\Service\AdvertService;
use App\Service\OrderService;
use Carbon\Carbon;

class OrderController
{
    public function store(Request $request,Response $response,$args){


        if ($request->isPost()) {
            $this->validator->request($request, [
                'advert_id' =>v::numeric(),
                'address' => v::stringType()->notEmpty(),
                'amount' => v::numeric()->length(1,20)->notEmpty()

            ]);
            $advert=AdvertService::get($request->getParam("advert_id"));
            $data = array_merge($request->getParams(), [
                'user_id' => $this->auth->getUser()->getUserId(),
                'order_code' => time(),
                'ad_id' => $advert->id,
                'ad_code' => "",
                'ad_user_id' => $advert->user_id,
                'buyer_estimate' => '',
                'seller_estimate' => '',
                'status' => 0,
                'coin_type'=>$advert->coin_type,
            ]);

            if ($advert->user_id==$this->auth->getUser()->getUserId()) {
                $this->validator->addError('用户', '不能自己给自己下单');
            }


            //获取可用余额
            $balance = UserBalance::where('user_id', $advert->user_id)
                ->where('coin_type', $advert->coin_type)
                ->first();

            $can_balance = $balance->total_balance - $balance->block_balance - $balance->pending_balance;

            if ($data["qty"] > $can_balance) {
                $this->validator->addError('此广告账户余额不足', '下单失败');

                //return $this->setMsg('此广告账户余额不足，下单失败')->setCode(2002)->toJson();
            }



            if ($this->validator->isValid()) {

                $order = OrderService::store($data);

                $orderService = new OrderService($order);
                $orderService->sellerlockOrder( );

                //放入监控
                Redis::command('hset', ['monitoring_order', $order->id,Carbon::now()]);
                Log::info('monitoring_order: '.$order->id);
                return $this->json($response, $address);

            }

            return $this->error($response);
        }
        return $this->error($response);









    }
}