@extends('layouts.frontBase')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('banner_menu_txt') 訂單查詢 > {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<form id="form_data_pay" class="tm-signup-form" method="post">
    @csrf
    <input type="hidden" id="action_type" name="action_type" value="pay">
    <input type="hidden" id="uuid" name="uuid" value="{{ @$datas["assign_data"]["uuid"] }}">
    <input type="hidden" id="payment" name="payment" value="">
</form>
<div class="row tm-mt-big">
    <div class="col-xl-12 col-lg-12 tm-md-12 tm-sm-12 tm-col">
        <div class="bg-white tm-block">
            <div class="row">
                <div class="col-12">
                    <h5 class="mt-3">{{ @$datas["assign_data"]["title_txt"] }}</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <label>選擇付款方式</label>
                    <div class="col-12">
                    @if(isset($datas["option_data"]["payment"]) && !empty($datas["option_data"]["payment"]))    
                        @foreach($datas["option_data"]["payment"] as $key => $val) 
                            <div class="form-check form-check-inline" onclick="changePayment();">
                                <input class="form-check-input" type="radio" name="payment" id="payment_{{ @$key }}" value="{{ @$key }}" @if($key == $datas["assign_data"]["payment"]) checked @endif>
                                <label class="form-check-label">{{ @$val }}</label>
                            </div>
                        @endforeach
                    @endif
                    </div>               
                </div>
            </div>
            <div class="table-responsive" style="margin-top: 10px;">
                <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
                <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
                @include('tables.orderUser')
            </div>
            <div class="table-responsive">
                @include('tables.orderCart')
            </div>
            <div class="row">
                <div class="col-12 col-sm-6"></div>
                <div class="col-12 col-sm-6 tm-btn-right">
                    <button type="button" class="btn btn-cart" onclick="orderSubmit('pay');">付款</button>
                    <button type="button" class="btn btn-danger" onclick="changeForm('/orders')">取消</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    changePayment();

    function changePayment() {
        payment = $('input[name=payment]:checked').val();
        $('#payment').val(payment);
    }
</script>
@endsection