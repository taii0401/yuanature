<?php

namespace App\Http\Middleware;

use Closure,DB;
use Illuminate\Http\Request;

use App\Models\Administrator;

class AuthAdmin
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
        
        if(session("admin") === "") { //未登入
            return redirect("/admin/");
        } else {
            $uuid = session("admin");

            $isLogIn = false;
            if($uuid != "") {
                $data = Administrator::where(["uuid" => $uuid,"status" => 1])->first();
                if(!empty($data)) {
                    $isLogIn = true;
                }
            }

            //未成功取得資料，強制登出
            if(!$isLogIn) {
                return redirect("/admin/");
            }
        }

        return $next($request);
    }
}
