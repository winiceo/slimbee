<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    protected $table='user_wallet';

    protected $fillable = [
        'user_id', 'coin_type', 'coin_name', 'lock_balance', 'pending_balance', 'total_balance'
    ];
}
