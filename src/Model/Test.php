<?php

namespace App\Models;

use App\Scopes\StatusScope;
use App\User;
use DB;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\Paginator;
use App\Exceptions\CouldNotMarkReplyAsSolution;

use Illuminate\Database\Eloquent\SoftDeletes;

class Test extends Model
{


    const TABLE = 'ads';

    protected $table = self::TABLE;


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function scopeWithOrder($query, $order)
    {
        // 不同的排序，使用不同的数据读取逻辑
        $query = $this->recent();
        // 预加载防止 N+1 问题
        return $query->with('user');
    }

    public function scopeRecent($query)
    {
        // 按照创建时间排序
        return $query->orderBy('created_at', 'desc');
    }

}
