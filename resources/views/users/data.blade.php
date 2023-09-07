@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('banner_menu_txt') {{ @$banner_menu_txt }} {{ @$title_txt }} @endsection
@section('content')
<div class="row tm-mt-big">
    @if($action_type != "add")
        @include('layouts.frontUser')
    @endif
    <div class="col-xl-10 col-lg-10 col-md-10 col-sm-12 mx-auto tm-login">
        <div class="bg-white tm-block">
            <div class="row">
                <div class="col-12 text-center">
                    <h5 class="mt-3">{{ @$title_txt }}</h5>
                </div>
            </div>
            <div class="col-12" style="display:{{ $edit_none}};">
                <div class="input-group mt-3 text-center">
                    <div class="col-6">
                        <button type="button" class="btn d-inline-block mx-auto text-center" style="border:0px;background-color:transparent;padding:0px;" onclick="changeForm('/users/third/fb_login')">
                            <img src="{{ asset('img/icons/fb_register.png') }}" width="135px" height="38px">
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn d-inline-block mx-auto text-center" style="border:0px;background-color:transparent;padding:0px;" onclick="changeForm('{{ @$line_url }}')">
                            <img src="{{ asset('img/icons/line_register.png') }}" width="135px" height="38px">
                        </button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
                <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
                <div class="col-12">
                    <form id="form_data" class="tm-signup-form" method="post">
                        @csrf
                        <input type="hidden" id="action_type" name="action_type" value="{{ @$action_type }}">
                        <input type="hidden" id="uuid" name="uuid" value="{{ @$uuid }}">
                        <div class="row input-group" style="display:{{ $edit_pass_none}};">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <label><span class="star">* </span>登入帳號(電子郵件)</label>
                                <input type="email" id="account" name="account" class="form-control {{ $pass_require }}" value="{{ @$account }}" {{ $disabled }}>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <label><span class="star">* </span>登入密碼</label>
                                <input type="password" id="password" name="password" class="form-control {{ $pass_require }}" value="" placeholder="輸入6~30個英文字或數字">                  
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <label><span class="star">* </span>確認密碼</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control {{ $pass_require }}" value="" placeholder="請輸入相同的登入密碼">
                            </div>
                        </div>
                        <div class="row input-group" style="display:{{ $edit_data_none}};">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <label><span class="star">* </span>姓名</label>
                                <input type="text" id="name" name="name" class="form-control {{ $require }}" value="{{ @$name }}">
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <label><span class="star">* </span>手機號碼</label>
                                <input type="text" id="phone" name="phone" class="form-control {{ $require }}" value="{{ @$phone }}">                  
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <label>生日</label>
                                <div class="input-group date" id="input_datetimepicker" data-target-input="nearest">
                                    <input type="text" id="birthday" name="birthday" class="form-control datetimepicker" data-target="#input_datetimepicker" value="{{ @$birthday }}" />
                                    <div class="input-group-append" data-target="#input_datetimepicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row input-group twzipcode" style="display:{{ $edit_data_none}};margin-top:10px;">
                            <input type="hidden" data-role="zipcode" id="address_zip" name="address_zip" class="form-control" value="{{ @$address_zip }}">
                            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                <label>地址</label>
                                <select class="custom-select " data-role="county" id="county" name="address_county"></select>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                <label><br/></label>
                                <select class="custom-select" data-role="district" id="district" name="address_district"></select>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-6 col-sm-12">
                                <label><br/></label>
                                <input type="text" id="address" name="address" class="form-control" placeholder="民族路民族街20巷10弄32號" value="{{ @$address }}">
                            </div>
                        </div>
                        <div class="row" style="display:{{ $add_none}};">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <label><span class="star">* </span>通知信箱</label>
                                <input type="text" id="email" name="email" class="form-control {{ $edit_require }}" value="{{ @$email }}">
                            </div>
                        </div>
                        <div class="row input-group mt-3">
                            <div class="col-12 tm-btn-center">
                                <button type="button" class="btn btn-danger btn_submit" onclick="userSubmit('{{ @$action_type }}');">送出</button>    
                                <button type="button" class="btn btn-primary" onclick="changeForm('/users/edit')">取消</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    //縣市、鄉鎮市區、郵遞區號
    const twzipcode = new TWzipcode();
    twzipcode.set("{{ @$address_zip }}");

    $(function () {
        $('.datetimepicker').datepicker({
            language: 'zh-TW', //中文化
            format: 'yyyy-mm-dd', //格式
            autoclose: true, //選擇日期後就會自動關閉
            todayHighlight: true //今天會有一個底色
        });
    });
</script>
@endsection
