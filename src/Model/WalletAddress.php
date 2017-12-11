<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletAddress extends Model
{
    public $timestamps = false;
    protected $connection = 'mysql';
    protected $table = 'wallet_address';
    protected $fillable = [
         'coin_type', 'address'
    ];
    protected $guarded = [];
}
