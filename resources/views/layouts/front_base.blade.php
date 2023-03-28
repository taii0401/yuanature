<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="keywords" content="原生學,Pure Nature,廣志足白浴露,原生,肌膚,純淨,自然,保養,回復,足白,清爽">
    <meta name="description" content="剛出生的寶寶肌膚最為稚嫩，經過環境、飲食、保養以及年齡的催化，肌膚的狀態不斷變化。我們相信「潔淨」是靜止肌膚狀態劣化的首要關鍵，水分及油脂的平衡打造更健康的肌膚，解決各種膚質問題，回復原生肌膚般的潔淨感受，我們希望帶給你們更多關於潔淨的體驗。">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        @include('layouts.front_header')
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