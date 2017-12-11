<?php
/**
 * Created by PhpStorm.
 * User: genv
 * Date: 2017/11/30
 * Time: 下午8:21
 */

namespace App\Service;


use App\Models\Order;
use App\Models\UserBalance;
use App\Repositories\OrderRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class OrderService
{
    protected $order;

    public function __construct($order)
    {

        $this->order = $order;

    }


    public function sellerSendMessage($content)
    {
        $from_user_id=$this->order->ad_user_id;
        $to_user_id=$this->order->user_id;


        $content = \GuzzleHttp\json_encode($content);

        $ret = app('rcloud')->Message()->publishPrivate(env('RONG_CLOUD_ID_PRE') . $from_user_id, env('RONG_CLOUD_ID_PRE') . $to_user_id, 'RC:TxtMsg', $content, '', '', '', 0, 1, 1, 1);
        return $ret;
    }

    public function buySendMessage($content){
        $from_user_id=$this->order->user_id;
        $to_user_id=$this->order->ad_user_id;


        $content = \GuzzleHttp\json_encode($content);

        $ret = app('rcloud')->Message()->publishPrivate(env('RONG_CLOUD_ID_PRE') . $from_user_id, env('RONG_CLOUD_ID_PRE') . $to_user_id, 'RC:TxtMsg', $content, '', '', '', 0, 1, 1, 1);
        return $ret;
    }
    //锁定币
    public   function sellerlockOrder( ){
        $uid=$this->order->ad_user_id;
        $coin_type=$this->order->coin_type;
        $block_balance=$this->order->qty;

        DB::table('user_balances')
            ->where('user_id',$uid)
            ->where('coin_type',$coin_type)
            ->increment('block_balance', $block_balance);


        Log::info('increment_balance: '.$block_balance);

    }

    //卖家解锁资金；
    public   function sellerUnlockOrder( ){
        $uid=$this->order->ad_user_id;
        $coin_type=$this->order->coin_type;
        $block_balance=$this->order->block_balance;


        DB::table('user_balances')
            ->where('user_id',$uid)
            ->where('coin_type',$coin_type)
            ->decrement('block_balance', $block_balance);

        Log::info('unlock_balance: '.$block_balance);


    }


    //放行订单
    public function orderRelease(){
        //卖单放行

        if($this->order->trade_type==0){
            $uid=$this->order->ad_user_id;
            $coin_type=$this->order->coin_type;
            $block_balance=$this->order->qty;



            //解锁
            DB::table('user_balances')
                ->where('user_id',$uid)
                ->where('coin_type',$coin_type)
                ->decrement('block_balance', $block_balance);

            //减少总资产
            DB::table('user_balances')
                ->where('user_id',$uid)
                ->where('coin_type',$coin_type)
                ->decrement('total_balance', $block_balance);


            DB::table('user_balances')
                ->where('user_id',$this->order->user_id)
                ->where('coin_type',$coin_type)
                ->increment('total_balance', $block_balance);


        }


    }

    //获取广告发布者可用资产
    public function getAdUserBalance($ad){
       $balance= UserBalance::findById('user_id',$ad->ad_user_id);


    }

    public static function check_order(){
        $orders=Redis::command('hgetall', ['monitoring_order']);


        foreach ($orders as $k=>$v){

            $order=Order::find($k);
            $dt=new Carbon($v);
            $time=$dt->diffInMinutes(Carbon::now());                  // 0

            if($time>$order->payment_window_minutes&&$order->status==Config::get('constants.ORDER_STATUS.CREATED')){


                Redis::command("hdel",['monitoring_order',$k]);
                $orderService = new OrderService($order);

                $orderService->sellerUnlockOrder();

            }

        }
    }

}