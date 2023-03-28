<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';
    protected $fillable = ['name','email','password'];
    protected $guarded = [];
    protected $hidden = ['password','remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * 取得名稱
     * @param  id
     * @return string
     */
    public static function getName($id)
    {
        $name = "";
        $data = self::where(["id" => $id])->first("name");
        if(isset($data) && $data->exists("name")) {
            $name = $data->name;
        }
        return $name;
    }

    /**
     * 取得名稱
     * @param  uuid
     * @return string
     */
    public static function getNameByUuid($uuid)
    {
        $name = "";
        $data = self::where(["uuid" => $uuid])->first("name");
        if(isset($data) && $data->exists("name")) {
            $name = $data->name;
        }
        return $name;
    }
}
