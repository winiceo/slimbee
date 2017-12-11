<?php
/**
 * Created by PhpStorm.
 * User: genv
 * Date: 2017/11/30
 * Time: 下午8:21
 */

namespace App\Service;


use App\Helpers\CoinHelpers;
use App\Model\User;
use App\Model\UserWallet;


class UserWalletService
{
    use CoinHelpers;


    public static function checkWallet(User $user)
    {

        $coins = CoinHelpers::get();

        foreach ($coins as $coin) {
            $balance = UserBalance::where('coin_type', $coin['value'])
                ->where('user_id', $user->id)
                ->first();
            if (!$balance) {
                $data["user_id"] = $user->id;
                $data["coin_type"] = $coin['value'];
                $data["coin_name"] = $coin['name'];
                $data["block_balance"] = 0;
                $data["pending_balance"] = 0;
                $data["total_balance"] = 0;
                UserBalance::create($data);
            }

        }

    }

    public static function address(User $user)
    {

        $coins = CoinHelpers::get();
        $balances = UserWallet::where('user_id', $user->id)
            ->get();
        return $balances;

    }


}