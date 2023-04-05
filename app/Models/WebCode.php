<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebpCode extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'web_code'; //指定資料表名稱
    protected $primaryKey = 'id'; //主鍵，Model會另外自動加入id
    protected $fillable = [
        'types','code','name','cname','is_display',
    ];
}
