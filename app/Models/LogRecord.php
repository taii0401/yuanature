<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogRecord extends Model
{
    use HasFactory;

    const ACTION_CREATE = "create";
    const ACTION_EDIT = "edit";
    const ACTION_DELETE = "delete";
    const ACTION_IMPORT = "import";
    const ACTION_EXPORT = "export";
    const ACTION_SEARCH = "search";
    const ACTION_OTHER = "other";

    public static $actionName = [
        self::ACTION_CREATE => "新增",
        self::ACTION_EDIT => "編輯",
        self::ACTION_DELETE => "刪除",
        self::ACTION_IMPORT => "匯入",
        self::ACTION_EXPORT => "匯出",
        self::ACTION_SEARCH => "查詢",
        self::ACTION_OTHER => "其他",
    ];

    protected $table = "log_record"; //指定資料表名稱
    protected $guarded = [];
    protected $casts = [
        "created_at" => "datetime:Y-m-d H:i:s",
        "updated_at" => "datetime:Y-m-d H:i:s"
    ];
}