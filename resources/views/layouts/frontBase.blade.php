<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="原生學,Pure Nature,消除腳臭,廣志足白浴露,原生,肌膚,純淨,自然,保養,回復,足白,清爽">
    <meta name="description" content="消除腳臭的根源、紓緩生活緊張的情緒，健康生活從深層的足部浸泡開始。肌膚的水分及油脂的平衡打造更健康的膚質，利用「深層潔淨」解決膚質所帶來的問題，回復原生肌膚般的舒適感受，潔淨的生活體驗，從腳開始。">
    <title>原生學 Pure Nature @yield('title')</title>
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
        @include('layouts.frontHeader')
        @yield('content')
        @include('layouts.footer')
    </div>
</body>
<script src="{{ mix('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ mix('js/popper.min.js') }}"></script>
<script src="{{ mix('js/bootstrap.min.js') }}"></script>
<script src="{{ mix('js/common.js') }}"></script>
<!-- 日期 -->
<script src="{{ mix('js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ mix('js/bootstrap-datepicker.zh-TW.min.js') }}"></script>
@yield('script')
</html>