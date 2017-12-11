<?php
/**
 * Created by PhpStorm.
 * User: genv
 * Date: 2017/12/10
 * Time: 下午11:08
 */

namespace App\Service;


use App\Model\Advert;
use App\Model\User;
use App\Repositories\AdvertRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Slim\Http\Request;

class AdvertService
{


    public static function search(Request $request, int $perPage = 20): Paginator
    {
        return Advert::with("user")
            ->where(function ($query) use ($request) {
                $trade_type = $request->getParam('trade_type', 0);
                $query->where('trade_type', $trade_type);
            })
            ->paginate($perPage);
    }

    public static function getByUser(Request $request,User $user, int $perPage = 20): Paginator
    {
        return Advert::with("user")
            ->where(function ($query) use ($request,$user) {

                $query->where('user_id', $user->id);
            })
            ->paginate($perPage);
    }


    public static function get($id)
    {
        return Advert::with('User')->findOrFail($id);
    }
}