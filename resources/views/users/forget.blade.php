@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('content')
<div class="content">
    <div class="col-12 mx-auto tm-login-col">
        <div class="bg-white tm-block">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="tm-block-title mt-3">{{ @$title_txt }}</h2>
                </div>
            </div>
            <hr>
            <div class="row">
                <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
                <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
                <div class="col-12">
                    <form id="form_data" class="tm-signup-form" method="post">
                        @csrf
                        <div class="input-group">
                            <label class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-form-label">帳號</label>
                            <input type="email" id="account" name="account" class="col-xl-10 col-lg-10 col-md-10 col-sm-12 form-control require" placeholder="電子郵件">
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 tm-btn-center">
                                <button type="button" class="btn btn-small btn-primary d-inline-block mx-auto" onclick="userForget();">送出</button>
                                <button type="button" class="btn btn-small btn-danger d-inline-block mx-auto" onclick="changeForm('/users')">取消</button>
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
