@extends('layouts.frontBase')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="content">
    <div class="row tm-mt-big">
        <div class="col-12 mx-auto tm-login">
            <div class="bg-white tm-block">
                <form id="form_data" class="tm-signup-form" method="post">
                    @csrf
                    <input type="hidden" id="action_type" name="action_type" value="add">
                    <input type="hidden" id="total" name="total" value="{{ @$datas["assign_data"]["total"] }}">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="tm-block-title">{{ @$datas["assign_data"]["title_txt"] }}&nbsp;&nbsp;總金額：{{ @$datas["assign_data"]["total"] }}</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
                        <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <label><span class="star">* </span>收件人-姓名</label>
                                    <input type="text" id="name" name="name" class="form-control require" value="{{ @$datas["assign_data"]["name"] }}">
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <label><span class="star">* </span>收件人-手機號碼</label>
                                    <input type="text" id="phone" name="phone" class="form-control require" value="{{ @$datas["assign_data"]["phone"] }}">                  
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <label>收件人-信箱</label>
                                    <input type="text" id="email" name="email" class="form-control" value="{{ @$datas["assign_data"]["email"] }}">
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <label><span class="star">* </span>付款方式</label>
                                    <div class="col-12">
                                    @if(isset($datas["option_data"]["payment"]) && !empty($datas["option_data"]["payment"]))    
                                        @foreach($datas["option_data"]["payment"] as $key => $val) 
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="payment" id="payment_{{ @$key }}" value="{{ @$key }}" @if($key == $datas["assign_data"]["payment"]) checked @endif>
                                                <label class="form-check-label">{{ @$val }}</label>
                                            </div>
                                        @endforeach
                                    @endif
                                    </div>                  
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <label><span class="star">* </span>配送方式</label>
                                    <div class="col-12">
                                    @if(isset($datas["option_data"]["delivery"]) && !empty($datas["option_data"]["delivery"]))    
                                        @foreach($datas["option_data"]["delivery"] as $key => $val) 
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="delivery" id="delivery_{{ @$key }}" value="{{ @$key }}" @if($key == $datas["assign_data"]["delivery"]) checked @endif onclick="changeDataDisplay('checked','delivery','orders_address','home',true);">
                                                <label class="form-check-label">{{ @$val }}</label>
                                            </div>
                                        @endforeach
                                    @endif
                                    </div>                  
                                </div>
                                <div id="div_orders_address" class="col-xl-4 col-lg-4 col-md-6 col-sm-12" style="display:none;">
                                    <label><span class="star">* </span>宅配地址</label>
                                    <input type="text" id="orders_address" name="address" class="form-control" value="{{ @$datas["assign_data"]["address"] }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label>備註：</label>
                                    <textarea id="order_remark" name="order_remark" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-6"></div>
                                <div class="col-12 col-sm-6 tm-btn-right">
                                    <button type="button" class="btn btn-primary btn_submit" onclick="changeForm('/orders/cart');">上一步</button>
                                    <button type="button" class="btn btn-danger btn_submit" onclick="orderSubmit('add')">結帳</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(function () {
        changeDataDisplay('checked','delivery','orders_address','home',true);
    });
</script>
@endsection