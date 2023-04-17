<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>原生學後台管理 @yield('title')</title>
    <!-- font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600">
    <link rel="stylesheet" href="{{ mix('css/fontawesome.min.css') }}">
    <!-- bootstrap -->
    <link rel="stylesheet" href="{{ mix('css/bootstrap.min.css') }}">
    <!-- 模版 -->
    <link rel="stylesheet" href="{{ mix('css/tooplate.css') }}">
    <!-- 日期 -->
    <link rel="stylesheet" href="{{ mix('css/bootstrap-datepicker.css') }}">
    @yield('css')
</head>

<body>
    <div class="container">
        <div class="header-outs">
            <div class="head-wl">
                <div class="search-w3ls">
                @if(AdminAuth::isLoggedIn())
                    <a href="/admin/edit">
                        <img src="{{ asset('img/icons/member.png') }}" height="20px">&nbsp;&nbsp;{{ AdminAuth::admindata()->name }}
                    </a>
                    <a href="/admin/logout">
                        / 登出
                    </a>
                @else
                    <a href="/admin/">
                        <img src="{{ asset('img/icons/member.png') }}" height="20px">&nbsp;&nbsp;登入
                    </a>
                @endif
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
        <div class="row" style="background-color: #bed0c0;">
            <div class="col-12">
                <nav class="navbar navbar-expand-md navbar-light" style="margin-top:0px;">
                    <a class="navbar-brand" href="/">
                        <img src="{{ asset('img/icons/logo.jpg') }}" height="80px">
                        <h6 class="tm-site-title mb-0">原生學後台管理-{{ @$datas["assign_data"]["title_txt"] }}</h6>
                    </a>    
                    <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent" style="background-color: #bed0c0;">
                        <ul class="navbar-nav mx-auto">
                        @if(AdminAuth::isLoggedIn())
                            <li class="nav-item" style="margin-top:15px">
                                <a class="nav-link" href="/admin/list">管理員管理</a>
                            </li>
                            <li class="nav-item" style="margin-top:15px">
                                <a class="nav-link" href="/admin/user">會員管理</a>
                            </li>
                            <li class="nav-item" style="margin-top:15px">
                                <a class="nav-link" href="/admin/order">訂單管理</a>
                            </li>
                        @endif
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        @yield('content')
        @include('layouts.footer')
    </div>
</body>
<script src="{{ mix('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ mix('js/popper.min.js') }}"></script>
<script src="{{ mix('js/bootstrap.min.js') }}"></script>
<script src="{{ mix('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ mix('js/common.js') }}"></script>
<!-- 日期 -->
<script src="{{ mix('js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ mix('js/bootstrap-datepicker.zh-TW.min.js') }}"></script>
@yield('script')
</html>