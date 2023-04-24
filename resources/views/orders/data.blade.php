@extends('layouts.frontBase')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="content">
    <form id="form_data" class="tm-signup-form" method="post">
        @csrf
        <input type="hidden" id="action_type" name="action_type" value="delete">
        <input type="hidden" id="product_id" name="product_id" value="">
        <input type="hidden" id="amount" name="amount" value="">
    </form>
    <div class="row tm-content-row tm-mt-big">
        <div class="col-xl-12 col-lg-12 tm-md-12 tm-sm-12 tm-col">
            <div class="bg-white tm-block h-100">
                <div class="row">
                    <div class="col-12">
                        <h2 class="tm-block-title">{{ @$datas["assign_data"]["title_txt"] }}</h2>
                    </div>
                </div>
                <div class="table-responsive" style="display:{{ @$datas["assign_data"]["cart_none"] }};">
                    <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:{{ @$datas["assign_data"]["danger_none"] }};">交易失敗</div>
                    <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:{{ @$datas["assign_data"]["success_none"] }};">交易成功</div>
                    <table class="table table-hover table-striped tm-table-striped-even mt-3"  style="vertical-align: middle;">
                        <thead>
                            <tr>
                                <th class="text-center tm-bg-gray" height="50px">訂單編號：</th>
                                <th>{{ @$datas["assign_data"]["serial"] }}</th>
                            </tr>
                            <tr>
                                <th class="text-center tm-bg-gray" height="50px">訂購日期：</th>
                                <th>{{ @$datas["assign_data"]["created_at_format"] }}</th>
                            </tr>
                            <tr>
                                <th class="text-center tm-bg-gray" height="50px">收件人姓名：</th>
                                <th>{{ @$datas["assign_data"]["name"] }}</th>
                            </tr>
                            <tr>
                                <th class="text-center tm-bg-gray" height="50px">收件人電話：</th>
                                <th>{{ @$datas["assign_data"]["phone"] }}</th>
                            </tr>
                            @if(@$datas["assign_data"]["delivery"] == "home" && @$datas["assign_data"]["address"] != "")
                                <tr>
                                    <th class="text-center tm-bg-gray" height="50px">收件人地址：</th>
                                    <th>{{ @$datas["assign_data"]["address"] }}</th>
                                </tr>
                            @endif
                            <tr>
                                <th class="text-center tm-bg-gray" height="50px">訂單狀態：</th>
                                <th>{{ @$datas["assign_data"]["status_name"] }}</th>
                            </tr>
                            @if(@$datas["assign_data"]["status"] == "cancel")
                                <tr>
                                    <th class="text-center tm-bg-gray" height="50px">取消原因：</th>
                                    <th>{{ @$datas["assign_data"]["cancel_name"] }}</th>
                                </tr>
                            @endif
                            <tr>
                                <th class="text-center tm-bg-gray" height="50px">配送方式：</th>
                                <th>{{ @$datas["assign_data"]["delivery_name"] }}</th>
                            </tr>
                            <tr>
                                <th class="text-center tm-bg-gray" height="50px">付款方式：</th>
                                <th>{{ @$datas["assign_data"]["payment_name"] }}</th>
                            </tr>
                            <tr>
                                <th class="text-center tm-bg-gray" height="50px">訂購金額：</th>
                                <th>{{ @$datas["assign_data"]["total"] }}元</th>
                            </tr>
                            <tr>
                                <th class="text-center tm-bg-gray" height="50px">訂單備註：</th>
                                <th>{!! @$datas["assign_data"]["order_remark_format"] !!}</th>
                            </tr>
                            @if(@$datas["assign_data"]["status"] == "cancel" && @$datas["assign_data"]["cancel"] == "other" && @$datas["assign_data"]["cancel_remark"] != "")
                                <tr>
                                    <th class="text-center tm-bg-gray" height="50px">取消備註：</th>
                                    <th>{!! @$datas["assign_data"]["cancel_remark_format"] !!}</th>
                                </tr>
                            @endif
                        </thead>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped tm-table-striped-even mt-3"  style="vertical-align: middle;">
                        <thead>
                            <tr class="tm-bg-gray">
                                <th scope="col" class="text-center" height="50px">商品名稱</th>
                                <th scope="col" class="text-center" style="width:10%;">數量</th>
                                <th scope="col" class="text-center" style="width:10%;">售價</th>
                                <th scope="col" class="text-center" style="width:15%;">小計</th>
                                <th scope="col" class="text-center" style="width:8%; display:{{ @$datas["assign_data"]["order_none"] }};">刪除</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($datas["cart_data"]) && !empty($datas["cart_data"]))
                                @foreach($datas["cart_data"] as $data) 
                                <tr>
                                    <td class="text-center tm-product-name" height="50px">{{ @$data["name"] }}</td>
                                    <td class="text-center" style="display:{{ @$datas["assign_data"]["order_none"] }};">
                                        <input type="number" min="1" id="amount_{{ @$data["id"] }}" name="amount[]" value="{{ @$data["amount"] }}" style="width: 50px;" onchange="cartChangeTotal('{{ @$data["id"] }}')">
                                    </td>
                                    <td class="text-center" style="display:{{ @$datas["assign_data"]["cart_none"] }};">{{ @$data["amount"] }}</td>
                                    <td class="text-center">
                                        <input type="hidden" id="price_{{ @$data["id"] }}" value="{{ @$data["price"] }}">    
                                        {{ @$data["price"] }}
                                    </td>
                                    <td class="text-center">
                                        <input type="hidden" id="subtotal_col_{{ @$data["id"] }}" name="subtotal[]" value="{{ @$data["subtotal"] }}">
                                        <span id="subtotal_{{ @$data["id"] }}">{{ @$data["subtotal"] }}</span>元
                                    </td>
                                    <td class="text-center" style="display:{{ @$datas["assign_data"]["order_none"] }};">
                                        <div class="col">
                                            <div class="btn-action">
                                                <i class="fas fa-trash-alt tm-trash-icon btn_submit" onclick="$('#product_id').val('{{ @$data["id"] }}');cartSubmit('delete');"></i>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <table class="table table-hover table-striped tm-table-striped-even mt-3"  style="vertical-align: middle;">
                        <thead>
                            <tr class="tm-bg-gray">
                                <th scope="col" height="50px">合計：<span id="total">{{ @$datas["assign_data"]["total"] }}</span>元</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6"></div>
                    <div class="col-12 col-sm-6 tm-btn-right" style="display:{{ @$datas["assign_data"]["order_none"] }};">
                        <button type="button" class="btn btn-primary" onclick="changeForm('/product')">繼續購買</button>
                        @if(UserAuth::isLoggedIn())
                            <button type="button" class="btn btn-danger" style="display:{{ @$datas["assign_data"]["btn_none"] }};" onclick="changeForm('/orders/pay_user');">下一步</button>
                        @else
                            <button type="button" class="btn btn-danger" style="display:{{ @$datas["assign_data"]["btn_none"] }};" onclick="alert('請先登入會員！');changeForm('/users')">下一步</button>
                        @endif
                    </div>
                    <div class="col-12 col-sm-6 tm-btn-right" style="display:{{ @$datas["assign_data"]["cart_none"] }};">
                        <button type="button" class="btn btn-primary" onclick="changeForm('/orders')">返回</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection