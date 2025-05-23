@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('banner_menu_txt') {{ @$title_txt }} @endsection
@section('content')
<div class="row">
    <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 mx-auto">
        <div class="row tm-block">
            <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
            <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
            <div class="row">
                <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
                    <div class="row">
                        <img id="img-box" src="../../img/product/product_sm_{{ $image }}.png" width="100%" style="max-width:500px;max-height:500px;">
                    </div>
                    <div class="row" style="margin-top: 10px;margin-bottom: 10px;max-width:500px;">
                        <div class="col-12 pdt-img-area">
                            <div style="display: flex;gap: 0.5em;">
                                @foreach(@$imgs as $img)
                                <div class="img-item" id="{{ $img }}"><img src="../../img/product/product_sm_{{ $img }}.png" alt="原生學 廣志足白浴露" width="100%"></div>
                                @endforeach
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12">
                    <h5 class="detail-title">{{ @$name }}</h5>
                    <p class="detail-text">去除雙腳異味，嫩白保溼呵護 15ml/入</p>
                    <p class="detail-text">
                        ｜舒緩肌膚不適感及舒緩雙腳的壓力<br>
                        ｜嫩白保濕肌膚<br>
                        ｜調理肌膚油水平衡<br>
                        ｜提升肌膚對環境傷害的保護力
                    </p>
                    <div class="row">
                        <div class="col-12">
                            <p class="detail-text" style="margin-top: 5px;">
                                規格：6入/盒(保存期限：3年)
                            </p>
                        </div>
                        <!-- 正常 -->
                        <div class="col-12" style="display: {{ @$price_block }};">
                            <span style="color:black;font-weight:900;font-size:1.3em;">
                                NT$ {{ @$price }}
                            </span>
                        </div>
                        <!-- 特價 -->
                        <div class="col-12" style="display: {{ @$sales_block }};">
                            <span style="color:#ff6201;">
                                <span style="font-size:1em;font-weight:900;">NT$</span>
                                <span style="font-size:1.8em;font-weight:900;">
                                    {{ @$sales }}
                                </span>
                                <del><span style="color:grey;font-size:1em;text-decoration:line-through red;">
                                    ${{ @$price }}
                                </span>
                                </del>
                            </span>
                            <br>
                            <span style="font-size: smaller;color:red;display:{{ @$sales_period }}">
                                優惠期間：2024/03/01 ~ 2024/03/31
                            </span>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-12">
                            <form id="form_data_cart" class="tm-signup-form" method="post">
                                @csrf
                                <input type="hidden" id="action_type" name="action_type" value="add">
                                <input type="hidden" id="product_id" name="product_id" value="{{ @$id }}">

                                <div class="div_number" id="div_number_1">
                                    <span class="minus" onclick="number_plus_minus('minus',{{ @$id }})">-</span>
                                    <input type="text" id="amount" name="amount" value="1" class="form-control">
                                    <span class="plus" onclick="number_plus_minus('plus',{{ @$id }})">+</span>
                                </div>
                            </form>
                        </div>
                        <div class="col-6" style="margin-top: 15px;">
                            <button type="button" class="btn btn-primary" style="width:100%" onclick="cartSubmit('add')">加入購物車</button>
                        </div>
                        <div class="col-6" style="margin-top: 15px;">
                            <button type="button" class="btn btn-cart" style="width:100%" onclick="cartSubmit('add')">立即購買</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"> </div>
            <div class="row" style="margin-top: 15px;">
                <div class="col-12">
                    <!-- tab標籤列 -->
                    <ul class="tab-title">
                        <li class="active">
                            <a href="javascript:;" data-tablink="one">商品說明</a>
                        </li>
                        <li>
                            <a href="javascript:;" data-tablink="two">使用方法</a>
                        </li>
                    </ul>
                    <!-- tab內容 -->
                    <div class="tab-inner-wrap" style="margin-top:-18px;">
                        <div id="one" class="tab-inner">
                            <iframe width="100%" height="400"
                                src="https://www.youtube.com/embed/3QwHq64zlYo?si=IwhI07FjOf6GDXI6&autoplay=1&mute=1">
                            </iframe>
                            @for($i = 1;$i <= 13;$i++)
                            <img src="../../img/product/product_detail_{{ $i }}.jpg" width="100%">
                            @endfor
                        </div>
                        <div id="two" class="tab-inner">
                            <img src="{{ asset('img/product/product_detail_use.jpg') }}" width="100%">
                        </div>
                    </div>
                </div>    
            </div>
            <div class="clearfix"> </div>
            <div class="row">
                <div class="col-12">
                    <p style="font-size:smaller; margin-top: 5px;">
                        製造日期：2022.11
                    </p>
                </div>  
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        tabCutover();
    });

    //切換圖片
    //獲取所有名為item的li標簽
    var items = document.getElementsByClassName("img-item");
    for(var i = 0; i < items.length; i++) {
        var item = items[i];
        item.index = i + 1;
        items[i].onclick = function () {
            document.getElementById('img-box').src = 'img/product/product_sm_'+this.id+'.png';
        }
    }

    //頁籤
    function tabCutover() {
        $(".tab-title li.active").each(function () {
            var tablink = $(this).find("a").data("tablink");
            $('#'+tablink).show().siblings(".tab-inner").hide();
        });

        $(".tab-title li").click(function () {
            $('#'+$(this).find("a").data("tablink")).show().siblings(".tab-inner").hide();
            $(this).addClass("active").siblings(".active").removeClass("active");
        });
    }
</script>
@endsection
