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


    /**
     * @api {post} /api/v2/adver 广告列表明
     * @apiName Advert_list
     * @apiGroup Advert

     * @apiParam {Number} coin_type 币种 (1为比特币，2为以太币).
     * @apiParam {Number} trade_type 交易类型 (0为出售 1为购买).
     * @apiParam {Number} limit 返回条数(1-100).
     * @apiParam {Number} limit 返回条数(1-100).
     * @apiParam {Number} page 页数(默认为1).
     * @apiParam {String} order_by 排序字段(默认为price).
     * @apiParam {Boolean} desc 是否降序排列(默认为1).
     *
     * @apiSuccess {Number} id 广告ID.
     * @apiSuccess {Number} user_id 用户ID.
     * @apiSuccess {Number} coin_type .
     * @apiSuccess {Number} trade_type 广告类型 0为出售 1为购买.
     * @apiSuccess {String} country_code 所在国家编号.
     * @apiSuccess {String} currency 交易币种.
     * @apiSuccess {String} margin 溢价.
     * @apiSuccess {String} price 价格.
     * @apiSuccess {String} min_price 最低价格.
     * @apiSuccess {String} min_amount 最小限额.
     * @apiSuccess {String} max_amount 最大限额.
     * @apiSuccess {String} payment_window_minutes 付款期限.
     * @apiSuccess {String} payment_provider 付款方式.
     * @apiSuccess {String} message 广告留言.
     * @apiSuccess {String} require_identification 是否启用安全选项.
     * @apiSuccess {String} require_trusted_by_advertiser 仅限受信任的交易者.

     * @apiSuccess {String} view_count 浏览次数.
     * @apiSuccess {String} status 状态.
     * @apiSuccess {String} published_at .
     * @apiSuccess {String} created_at .
     * @apiSuccess {String} updated_at .
     * @apiSuccess {String} deleted_at .

     * @apiSampleRequest /api/v2/adver
     */

    public function overview(Request $request,Response $response)
    {
        $adverts = AdvertService::search($request,20);
        return $this->json($response,$adverts);
    }

    /**
     * @api {get} /api/v2/adver/{id} 广告明细
     * @apiName AdvertDetail
     * @apiGroup Advert
     * @apiParam {Int} id 广告Id.
     * @apiSampleRequest /api/v2/advert
     */

    public function show(Request $request,Response $response,$id)
    {
        $advert = AdvertService::get($id);

        return $this->json($response,$advert);
    }

  /**
   * @api {get} /api/v2/user/adverts 用户所有广告列表
   * @apiGroup Advert
   * @apiHeader {String} access-key Users unique access-key.
    */
    public function getByUser(Request $request,Response $response){

        $adverts= AdvertService::getByUser($request,$this->user);
        $coins=CoinHelpers::getIds();
        foreach ($adverts as $k=>$v){
            $adverts[$k]->coin_name=$coins[$v->coin_type]['name'];
        }

        return  $this->json($response,$adverts);

    }



}
