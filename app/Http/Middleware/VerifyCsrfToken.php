<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        "/orders/pay_mpg_return",
        "/orders/pay_notify",
        "/orders/pay_customer",
        "/orders/store_map_callback",
        "/orders/logistic_code_callback",
        "/orders/pay_callback",
        "/orders/pay_info_callback",
        "/orders/line_pay_confirm",
    ];
}
