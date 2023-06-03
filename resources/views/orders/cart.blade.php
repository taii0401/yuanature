@extends('layouts.frontBase')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('banner_menu_txt') {{ @$datas["assign_data"]["banner_menu_txt"] }} {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
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

                <form id="form_data_coupon" class="tm-signup-form" method="post">
                    @csrf
                    <input type="hidden" id="action_type" name="action_type" value="coupon">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <label class="col-form-label">使用折價劵</label>
                            <select class="custom-select" style="width:70%;height:40px;" id="selsct_user_coupon" name="user_coupon_id">
                                <option value="">請選擇</option>
                                @if(isset($datas["user_coupon_data"]) && !empty($datas["user_coupon_data"]))
                                    @foreach($datas["user_coupon_data"] as $key => $val)
                                        <option value="{{ $key }}" >{{ $val }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <label><span class="star">* </span>配送方式<span style="color:red;font-size:x-small"> (2萬元以上只可選擇宅配)</span></label>
                            <div class="col-12">
                            @if(isset($datas["option_data"]["delivery"]) && !empty($datas["option_data"]["delivery"]))    
                                @foreach($datas["option_data"]["delivery"] as $key => $val) 
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="delivery" id="delivery_{{ @$key }}" value="{{ @$key }}" {{ @$datas["assign_data"]["delivery_disabled"] }} @if($key == $datas["assign_data"]["delivery"]) checked @endif>
                                        <label class="form-check-label">{{ @$val }}</label>
                                    </div>
                                @endforeach
                            @endif
                            </div>                  
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <label><span class="star">* </span>台灣本島或離島</label>
                            <div class="col-12">
                            @if(isset($datas["option_data"]["island"]) && !empty($datas["option_data"]["island"]))    
                                @foreach($datas["option_data"]["island"] as $key => $val) 
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="island" id="island_{{ @$key }}" value="{{ @$key }}" @if($key == $datas["assign_data"]["island"]) checked @endif>
                                        <label class="form-check-label">{{ @$val }}</label>
                                    </div>
                                @endforeach
                            @endif
                            </div>                  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-8 col-lg-8 col-md-6 col-sm-12">
                            <span style="font-size: smaller;color:red;">
                                台灣本島：滿1500免運費，宅配：100元，超商取貨：70元<br>
                                台灣離島：滿2000免運費，宅配：150元，超商取貨：110元
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row input-group mt-3">
                <div class="col-12 tm-btn-right" style="display:{{ @$datas["assign_data"]["cart_display"] }};">
                    <button type="button" class="btn btn-primary" onclick="changeForm('/product')">繼續購買</button>
                    @if(UserAuth::isLoggedIn())
                        <button type="button" class="btn btn-danger" onclick="cartSubmit('coupon');">下一步</button>
                    @else
                        <button type="button" class="btn btn-danger" onclick="alert('請先登入會員！');changeForm('/users')">下一步</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(function () {
        //選取折價劵
        $('#selsct_user_coupon').val('{{ @$datas["assign_data"]["user_coupon_id"] }}');
        //更新配送方式
        changeDelivery($('#origin_total').val());
    });
</script>
@endsection