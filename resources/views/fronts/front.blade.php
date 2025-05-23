@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('banner_menu_txt') {{ @$title_txt }} @endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('packages/slick/slick.css') }}"/>
<style>
    /*圖片輪播 START*/
    /** {
        padding:0px;
        margin:0px;
        border:0px;
        box-sizing: border-box;
    }*/

    .slider {
        width: 50%;
        margin: 100px auto;
    }

    .slick-slide {
      margin: 0px 20px;
    }

    .slick-slide img {
      width: 100%;
    }

    .slick-prev:before,
    .slick-next:before {
      color: black;
    }

    .slick-slide {
      transition: all ease-in-out .3s;
      opacity: .2;
    }
    
    .slick-active {
      opacity: .5;
    }

    .slick-current {
      opacity: 1;
    }
    /*圖片輪播 END*/
</style>
@endsection
@section('content')
<div class="row" style="padding-top: 10px;">
    <div class="col-xl-1 col-lg-1 col-md-12 col-sm-12"></div>
    <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12">
        <section class="variable">
            @foreach(@$imgs as $img)
                <a href="product">    
                    <img class="img_web" src="../../img/index/indexpic{{ $img }}.jpg" alt="原生學 廣志足白浴露" style="display: block;">
                    <img class="img_mobile" src="../../img/index/indexpic{{ $img }}_m.jpg" alt="原生學 廣志足白浴露" style="display: none;">
                </a>
            @endforeach
        </section>
    </div>
    <div class="col-xl-1 col-lg-1 col-md-12 col-sm-12"></div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{ mix('packages/slick/slick.js') }}"></script>
<script>
    $(document).ready(function(){
        if(navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i)) {
            $('.img_mobile').css('display', 'block');
            $('.img_web').css('display', 'none');
        } else {
            $('.img_web').css('display', 'block');
            $('.img_mobile').css('display', 'none');
        }
        $('.variable').slick({
            autoplay: true,
            autoplaySpeed: 3000,
            dots: true,
            infinite: true,
            speed: 500,
            fade: true,
            cssEase: 'linear',
            settings: {
                dots: false
            }
        });
    });
</script>
@endsection