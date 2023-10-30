@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('banner_menu_txt') {{ @$title_txt }} @endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('packages/slick/slick.css') }}"/>
<style>
    /*圖片輪播 START*/
    * {
        padding:0px;
        margin:0px;
        border:0px;
        box-sizing: border-box;
    }

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
            @for($i = 1;$i <= 3;$i++)
                <img src="../../img/index/indexpic0{{ $i }}.jpg">
            @endfor
        </section>
    </div>
    <div class="col-xl-1 col-lg-1 col-md-12 col-sm-12"></div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{ mix('packages/slick/slick.js') }}"></script>
<script>
    $(document).ready(function(){
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