<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="bjjQK3EQcbaHnoRNBSP0-vWAq6gRoCm1azM2XdV0z8s" />
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

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-Y080K8RF87"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-Y080K8RF87', {'allow_display_features': true});
    </script>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-MQJ2CRRM');</script>
    <!-- End Google Tag Manager -->
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MQJ2CRRM"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    
    @include('layouts.frontHeader')
    <div class="content">
        @if(@$show_banner_menu == "N")
            <div>
        @else
            <div class="banner_menu">
                <a href="/">
                    <img src="{{ asset('img/icons/home.png') }}" height="25px">
                </a>
                &nbsp;>&nbsp;
                @yield('banner_menu_txt')
            </div>
            <hr>
            <div style="margin-top:-0.5rem;">
        @endif
            @yield('content')
        </div>
    </div>
    @include('layouts.footer')
</body>

<script>
    //console.log(window.navigator);
    if(navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i)) {
        //alert('phone');
    } else {
        //alert('computer');
    }
</script>
<script src="{{ mix('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ mix('js/popper.min.js') }}"></script>
<script src="{{ mix('js/bootstrap.min.js') }}"></script>
<script src="{{ mix('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ mix('js/common.js') }}"></script>
<!-- 日期 -->
<script src="{{ mix('js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ mix('js/bootstrap-datepicker.zh-TW.min.js') }}"></script>
<!-- 郵遞區號 -->
<script src="{{ mix('js/twzipcode.js') }}"></script>
<script src="{{ mix('js/twzipcode.min.js') }}"></script>
@yield('script')
</html>