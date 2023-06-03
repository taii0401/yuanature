@extends('layouts.frontBase')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('banner_menu_txt') 購物車 > {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="row tm-mt-big">
    <div class="col-12 mx-auto tm-login">
        <div class="bg-white tm-block">
            <form id="form_data_cart" class="tm-signup-form" method="post">
                @csrf
                <input type="hidden" id="action_type" name="action_type" value="order">
                <input type="hidden" id="user_coupon_id" name="user_coupon_id" value="{{ @$datas["assign_data"]["user_coupon_id"] }}">
                <input type="hidden" id="delivery" name="delivery" value="{{ @$datas["assign_data"]["delivery"] }}">
                <input type="hidden" id="island" name="island" value="{{ @$datas["assign_data"]["island"] }}">
                <div class="row">
                    <div class="table-responsive">
                        @include('tables.orderTotal')
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
                        <div class="row" style="display:{{ @$datas["assign_data"]["address_display"] }};">
                            <div id="div_address" class="row input-group twzipcode" style="margin-top:10px;">
                                <input type="hidden" data-role="zipcode" id="address_zip" name="address_zip" class="form-control" value="{{ @$datas["assign_data"]["address_zip"] }}">
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                    <label><span class="star">* </span>地址</label>
                                    <select class="custom-select " data-role="county" id="county" name="address_county" onclick="changeIsland()"></select>
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
                                <textarea id="order_remark" name="order_remark" class="form-control">{!! @$datas["assign_data"]["order_remark"] !!}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6"></div>
                            <div class="col-12 col-sm-6 tm-btn-right">
                                <button type="button" class="btn btn-primary btn_submit" onclick="changeForm('/orders/cart');">上一步</button>
                                <button type="button" class="btn btn-danger btn_submit" onclick="cartSubmit('cart_user');">下一步</button>
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
</script>
@endsection