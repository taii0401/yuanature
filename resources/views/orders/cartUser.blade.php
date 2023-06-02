@extends('layouts.frontBase')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('banner_menu_txt') 購物車 > {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="row tm-mt-big">
    <div class="col-12 mx-auto tm-login">
        <div class="bg-white tm-block">
            <form id="form_data" class="tm-signup-form" method="post">
                @csrf
                <input type="hidden" id="action_type" name="action_type" value="order">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered table-rwd" style="background-color: #F2FFFF;">
                            <thead>    
                                <tr class="tr-only-hide text-center">
                                    <th>商品金額</th>
                                    <th>折價金額</th>
                                    <th>運費</th>
                                    <th>總金額</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="td-data-span">商品金額：</span>
                                        <input type="hidden" id="product_total" name="product_total" value="{{ @$datas["assign_data"]["product_total"] }}">
                                        {{ @$datas["assign_data"]["product_total"] }}元
                                    </td>            
                                    <td>
                                        <span class="td-data-span">折價金額：</span>
                                        <input type="hidden" id="coupon_total" name="coupon_total" value="{{ @$datas["assign_data"]["coupon_total"] }}">
                                        {{ @$datas["assign_data"]["coupon_total"] }}元
                                    </td>
                                    <td>
                                        <span class="td-data-span">運費：</span>
                                        <input type="hidden" id="delivery_total" name="delivery_total" value="{{ @$datas["assign_data"]["delivery_total"] }}">
                                        {{ @$datas["assign_data"]["delivery_total"] }}元
                                    </td>
                                    <td>
                                        <span class="td-data-span">總金額：</span>
                                        <input type="hidden" id="total" name="total" value="">
                                        <span id="total_text">{{ @$datas["assign_data"]["total"] }}</span>元
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
                    <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <label><span class="star">* </span>收件人姓名</label>
                                <input type="text" id="name" name="name" class="form-control require" value="{{ @$datas["assign_data"]["name"] }}">
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <label><span class="star">* </span>收件人手機</label>
                                <input type="text" id="phone" name="phone" class="form-control require" value="{{ @$datas["assign_data"]["phone"] }}">                  
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <label><span class="star">* </span>收件人信箱</label>
                                <input type="text" id="email" name="email" class="form-control require" value="{{ @$datas["assign_data"]["email"] }}">
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
                                <label><span class="star">* </span>配送方式<span style="color:red;font-size:x-small"> (2萬元以上只可選擇宅配)</span></label>
                                <div class="col-12">
                                @if(isset($datas["option_data"]["delivery"]) && !empty($datas["option_data"]["delivery"]))    
                                    @foreach($datas["option_data"]["delivery"] as $key => $val) 
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="delivery" id="delivery_{{ @$key }}" value="{{ @$key }}" {{ @$datas["assign_data"]["delivery_disabled"] }} @if($key == $datas["assign_data"]["delivery"]) checked @endif onclick="changeDataDisplay('checked','delivery','address','home',true);">
                                            <label class="form-check-label">{{ @$val }}</label>
                                        </div>
                                    @endforeach
                                @endif
                                </div>                  
                            </div>
                            <div id="div_address" class="row input-group twzipcode" style="display:none;margin-top:10px;">
                                <input type="hidden" data-role="zipcode" id="address_zip" name="address_zip" class="form-control" value="{{ @$datas["assign_data"]["address_zip"] }}">
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                    <label><span class="star">* </span>地址</label>
                                    <select class="custom-select " data-role="county" id="county" name="address_county"></select>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                    <label><br/></label>
                                    <select class="custom-select" data-role="district" id="district" name="address_district"></select>
                                </div>
                                <div class="col-xl-7 col-lg-7 col-md-6 col-sm-12">
                                    <label><br/></label>
                                    <input type="text" id="address" name="address" class="form-control" placeholder="民族路20巷32號" value="{{ @$datas["assign_data"]["address"] }}">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px;">
                            <div class="col-12">
                                <label>備註</label>
                                <textarea id="order_remark" name="order_remark" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6"></div>
                            <div class="col-12 col-sm-6 tm-btn-right">
                                <button type="button" class="btn btn-primary btn_submit" onclick="changeForm('/orders/cart');">上一步</button>
                                <button type="button" class="btn btn-danger btn_submit" onclick="cartSubmit('order');">下一步</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    //縣市、鄉鎮市區、郵遞區號
    const twzipcode = new TWzipcode();
    twzipcode.set("{{ @$datas["assign_data"]["address_zip"] }}");

    $(function () {
        //計算總金額
        cartChangeTotal();

        //配送方式-宅配配送顯示收件人地址
        changeDataDisplay('checked','delivery','address','home',true);
    });
</script>
@endsection