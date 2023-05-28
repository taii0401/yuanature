<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//LOG
use Illuminate\Support\Facades\Log;
//字串-UUID
use Illuminate\Support\Str;
//例外處理
use Illuminate\Database\QueryException;
//上傳檔案
use Illuminate\Support\Facades\Storage;

class WebFile extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "web_file"; //指定資料表名稱
    protected $primaryKey = "id"; //主鍵，Model會另外自動加入id
    protected $guarded = [];

    /**
     * 上傳檔案
     * @param  file_data：檔案
     * @return boolean
     */
    public static function uploadFile($file_data=[])
    {
        $error = true;
        $message = "新增檔案失敗！";
        $name = $file_id = "";

        if(!empty($file_data)) {
            //存放料夾名稱
            $folder_name = $file_data["folder_name"]??"files";
            //檔案資料
            $files = $file_data["file"]??[];
            if(!empty($files)) {
                //檔案名稱
                $name = $files->getClientOriginalName();
                //檔案大小
                $size = $files->getSize();
                //檔案型態
                $str = explode(".",$name);
                $types = isset($str[1])?$str[1]:"";
                //新檔案名稱
                $file_name = substr(Str::uuid()->toString(),0,8)."_".date("YmdHis").".".$types;

                //檔案存放路徑
                $disk_name = "public";
                //將檔案存在./storage/public/$folder_name/，並將檔名改為$file_name
                $path = $files->storeAs(
                    $folder_name,
                    $file_name,
                    $disk_name
                );
                //print_r($path);

                try {
                    //新增檔案
                    $data = [];
                    $data["name"] = $name;
                    $data["file_name"] = $file_name;
                    $data["path"] = $disk_name."/".$path;
                    $data["size"] = $size;
                    $data["types"] = $types;
                    $insert_data = self::create($data);
                    $file_id = (int)$insert_data->id;

                    if($file_id > 0) { //新增成功
                        $error = false;
                        $message = "新增檔案成功！";
                    } else {
                        //刪除檔案存放路徑
                        $file_path = "public/".$folder_name."/".$file_name;
                        if(Storage::exists($file_path)) {
                            Storage::delete($file_path);
                        }
                    }
                } catch(QueryException $e) {
                    Log::Info("新增檔案失敗：路徑 - ".$path);
                    Log::error($e);
                }
            }
        }

        $return_data = [];
        $return_data["error"] = $error;
        $return_data["message"] = $message;
        $return_data["file_name"] = $name;
        $return_data["file_id"] = $file_id;

        return $return_data;
    }

    /**
     * 刪除檔案及實際路徑
     * @param  file_ids：檔案ID
     * @return boolean
     */
    public static function deleteFile($file_ids=[])
    {
        $isSuccess = true;
        if(!empty($file_ids)) {
            $file_datas = self::whereIn("id",$file_ids)->get()->toArray();
            if(!empty($file_datas)) {
                foreach($file_datas as $file_data) {
                    $file_id = isset($file_data["id"])?$file_data["id"]:"";
                    //刪除檔案存放路徑
                    $file_path = isset($file_data["path"])?$file_data["path"]:"";
                    if(Storage::exists($file_path)) {
                        Storage::delete($file_path);
                    }
                    //刪除檔案
                    $destroy = self::destroy($file_id);
                    if(!$destroy) {
                        $isSuccess = false;
                    }
                }
            }
        }

        return $isSuccess;
    }
}
