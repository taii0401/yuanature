@extends('layouts.frontBase')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('banner_menu_txt') {{ @$datas["assign_data"]["banner_menu_txt"] }} {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<form id="form_data" class="tm-signup-form" method="post">
    @csrf
    <input type="hidden" id="action_type" name="action_type" value="add">
    @if(isset($datas["assign_data"]))
        @foreach($datas["assign_data"] as $key => $val)
            @if(in_array($key,["user_coupon_id","delivery","island","origin_total","coupon_total","delivery_total","total","name","phone","email","address_zip","address_county","address_district","address"]))
                <input type="hidden" id="{{ $key }}" name="{{ $key }}" value="{{ $val }}">
            @elseif($key == "order_remark")
                <textarea id="{{ $key }}" name="{{ $key }}" style="display:none">{!! $val !!}</textarea>
            @endif
        @endforeach
    @endif
</form>
<div class="row tm-mt-big">
    <div class="col-xl-12 col-lg-12 tm-md-12 tm-sm-12 tm-col">
        <div class="bg-white tm-block">
            <div class="row">
                <div class="col-12">
                    <h5 class="mt-3">{{ @$datas["assign_data"]["title_txt"] }}</h5>
                </div>
            </div>
            <div class="table-responsive">
                <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
                <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
                <!--<div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:{{ @$datas["assign_data"]["danger_none"] }};">交易失敗</div>
                <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:{{ @$datas["assign_data"]["success_none"] }};">交易成功</div>-->
                @include('tables.orderUser')
            </div>
            <div class="table-responsive">
                @include('tables.orderCart')
            </div>
            <div class="row">
                <div class="col-12 col-sm-6"></div>
                <div class="col-12 col-sm-6 tm-btn-right">
                    <button type="button" class="btn btn-primary" onclick="changeForm('/orders/cart_user')">上一步</button>
                    <button type="button" class="btn btn-cart" onclick="orderSubmit('add_pay')">立即結帳</button>
                    <button type="button" class="btn btn-primary" onclick="orderSubmit('add');">稍後付款</button>
                    <button type="button" class="btn btn-danger" onclick="changeForm('/orders/cart_cancel')">取消</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection