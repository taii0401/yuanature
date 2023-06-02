@extends('layouts.frontBase')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('banner_menu_txt') {{ @$datas["assign_data"]["banner_menu_txt"] }} {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<form id="form_data" class="tm-signup-form" method="post">
    @csrf
    <input type="hidden" id="action_type" name="action_type" value="delete">
    <input type="hidden" id="product_id" name="product_id" value="">
    <input type="hidden" id="amount" name="amount" value="">
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
                @include('tables.orderCart')
                <div class="row">
                    @if(isset($datas["user_coupon_data"]) && !empty($datas["user_coupon_data"]))
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <label class="col-form-label">使用折價劵</label>
                            <select class="custom-select" style="width:60%;" id="coupon_id" name="coupon_id">
                                <option value="">請選擇</option>
                                @foreach($datas["user_coupon_data"] as $user_coupon_data)
                                    @if(isset($user_coupon_data["id"]) && $user_coupon_data["id"] > 0)
                                        <option value="{{ @$user_coupon_data["id"] }}">{{ @$user_coupon_data["coupon_name"] }} - {{ @$user_coupon_data["total"] }}元</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-xl-8 col-lg-8 col-md-6 col-sm-12">
                        <span style="font-size: smaller;color:red;">
                            台灣本島：滿1500免運費，宅配：100元，超商取貨：70元<br>
                            台灣離島：滿2000免運費，宅配：150元，超商取貨：110元
                        </span>
                    </div>
                </div>
            </div>
            <div class="row input-group mt-3">
                <div class="col-12 tm-btn-right" style="display:{{ @$datas["assign_data"]["cart_display"] }};">
                    <button type="button" class="btn btn-primary" onclick="changeForm('/product')">繼續購買</button>
                    @if(UserAuth::isLoggedIn())
                        <button type="button" class="btn btn-danger" onclick="changeForm('/orders/cart_user');">下一步</button>
                    @else
                        <button type="button" class="btn btn-danger" onclick="alert('請先登入會員！');changeForm('/users')">下一步</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection