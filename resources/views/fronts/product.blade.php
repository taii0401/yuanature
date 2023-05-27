@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('banner_menu_txt') {{ @$title_txt }} @endsection
@section('content')
<div class="row">
    <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12 mx-auto">
        <div class="row tm-block">
            <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
            <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
            <div class="row">
                <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
                    <div class="row">
                        <img id="img_box" src="{{ asset('img/product/product_sm_1.png') }}" width="100%" style="max-width:580px;max-height:500px;">
                    </div>
                    <div class="row" style="margin-top: 10px;margin-bottom: 10px;max-width:580px;">
                        <div class="col-12 pdt_img_area">
                            <ul>
                                <li class="img_item"><img src="{{ asset('img/product/product_sm_1.png') }}"></li>
                                <li class="img_item"><img src="{{ asset('img/product/product_sm_2.png') }}"></li>
                                <li class="img_item"><img src="{{ asset('img/product/product_sm_3.png') }}"></li>
                                <li class="img_item"><img src="{{ asset('img/product/product_sm_4.png') }}"></li>
                                <li class="img_item"><img src="{{ asset('img/product/product_sm_5.png') }}"></li>
                            </ul>
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
                            <p class="detail-text" style="margin-top: 20px;">
                                製造日期：2022.11 保存期限：3年<br>
                                規格：6入/盒
                            </p>
                        </div>  
                        <div class="col-12">
                            <h5 style="color:black;font-weight:900;">NT ${{ @$sales }}</h5>
                        </div>                                
                    </div>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-12">
                            <form id="form_data" class="tm-signup-form" method="post">
                                @csrf
                                <input type="hidden" id="action_type" name="action_type" value="add">
                                <input type="hidden" id="product_id" name="product_id" value="{{ @$id }}">
                                <input type="number" min="1" id="amount" name="amount" value="1" class="form-control" width="100%;">
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
                    <div class="tab-inner-wrap">
                        <div id="one" class="tab-inner">
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
    var items = document.getElementsByClassName("img_item");
    for(var i = 0; i < items.length; i++) {
        var item = items[i];
        item.index = i + 1;
        items[i].onclick = function () {
            document.getElementById('img_box').src = 'img/product/product_sm_'+this.index+'.png';
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
