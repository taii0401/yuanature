@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('content')
<div class="content">
    <div class="row tm-mt-big">
        @if($action_type != "add")
            @include('layouts.frontUser')
        @endif
        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 mx-auto tm-login">
            <div class="bg-white tm-block">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2 class="tm-block-title mt-3">{{ @$title_txt }}</h2>
                    </div>
                </div>
                <div class="col-12" style="display:{{ $edit_none}};">
                    <div class="input-group mt-3 text-center">
                        <div class="col-6">
                            <button type="button" class="btn d-inline-block mx-auto text-center" style="border:0px;background-color:transparent;padding:0px;" onclick="changeForm('/users/third/fb_login')">
                                <img src="{{ asset('img/icons/fb_register.gif') }}" width="135px" height="50px">
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn d-inline-block mx-auto text-center" style="border:0px;background-color:transparent;padding:0px;" onclick="changeForm('{{ @$line_url }}')">
                                <img src="{{ asset('img/icons/line_register.gif') }}" width="135px" height="50px">
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
                            <div class="row" style="display:{{ $edit_pass_none}};">
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <label><span class="star">* </span>登入帳號(電子郵件)</label>
                                    <input type="email" id="account" name="account" class="form-control require" value="{{ @$account }}" {{ $disabled }}>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <label><span class="star">* </span>登入密碼</label>
                                    <input type="password" id="password" name="password" class="form-control {{ $require }}" value="" placeholder="輸入6~30個英文字或數字">                  
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <label><span class="star">* </span>確認密碼</label>
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control {{ $require }}" value="" placeholder="請輸入相同的登入密碼">
                                </div>
                            </div>
                            <div class="row" style="display:{{ $edit_data_none}};">
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <label>姓名</label>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ @$name }}">
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <label>手機號碼</label>
                                    <input type="text" id="phone" name="phone" class="form-control" value="{{ @$phone }}">                  
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <label>生日</label>
                                    <div class="input-group date" id="input_datetimepicker" data-target-input="nearest">
                                        <input type="text" id="birthday" name="birthday" class="form-control datetimepicker" data-target="#input_datetimepicker"  value="{{ @$birthday }}" />
                                        <div class="input-group-append" data-target="#input_datetimepicker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="display:{{ $add_none}};">
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <label>通知信箱</label>
                                    <input type="text" id="email" name="email" class="form-control" value="{{ @$email }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12 tm-btn-center">
                                    <button type="button" class="btn btn-primary" onclick="userSubmit('{{ @$action_type }}');">送出</button>
                                    <button type="button" class="btn btn-danger" onclick="changeForm('/users/edit')">取消</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
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
