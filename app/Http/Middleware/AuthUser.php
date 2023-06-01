<?php

namespace App\Http\Middleware;

use Closure,DB;
use Illuminate\Http\Request;

use App\Models\WebUser;

class AuthUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //echo "<pre>";print_r(session()->all());echo "</pre>";
        
        if(session("user") === "") { //未登入
            return redirect("users");
        } else {
            $uuid = session("user");

            $isLogIn = false;
            if($uuid != "") {
                $data = WebUser::where(["uuid" => $uuid,"is_verified" => 1])->first();
                if(!empty($data)) {
                    $isLogIn = true;
                }
            }

            //未成功取得資料，強制登出
            if(!$isLogIn) {
                return redirect("users");
            }
        }

        return $next($request);
    }
}
