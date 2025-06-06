@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('banner_menu_txt') {{ @$title_txt }} @endsection
@section('content')
<div class="row tm-mt-big">
    <div class="col-12 mx-auto tm-login-col">
        <div class="bg-white tm-block">
            <div class="row">
                <div class="col-12 text-center">
                    <h5 class="mt-3">{{ @$title_txt }}</h5>
                </div>
            </div>
            <div class="row mt-2">
                @if($errors->any())
                    @foreach($errors->all() as $message)
                        <div id="msg_error" class="col-12 alert alert-danger" role="alert">{{ $message }}</div>
                    @endforeach
                @endif
                <div class="col-12">
                    <div class="input-group mt-3 text-center">
                        <div class="col-6">
                            <button type="button" class="btn d-inline-block mx-auto text-center" style="border:0px;background-color:transparent;padding:0px;" onclick="changeForm('/users/third/fb_login')">
                                <img src="{{ asset('img/icons/fb_login.png') }}" width="135px" height="38px">
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn d-inline-block mx-auto text-center" style="border:0px;background-color:transparent;padding:0px;" onclick="changeForm('{{ @$line_url }}')">
                                <img src="{{ asset('img/icons/line_login.png') }}" width="135px" height="38px">
                            </button>
                        </div>
                    </div>
                    <hr>

                    <form id="form_data" method="post" class="tm-login-form" action="{{ route('users.login') }}">
                        @csrf
                        <div class="input-group">
                            <label class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-form-label">帳號</label>
                            <input type="email" id="account" name="account" class="col-xl-7 col-lg-7 col-md-7 col-sm-12 form-control require" placeholder="電子郵件">
                        </div>
                        <div class="input-group mt-3">
                            <label class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-form-label">密碼</label>
                            <input type="password" id="password" name="password" placeholder="輸入6~30個英文字或數字" class="col-xl-7 col-lg-7 col-md-7 col-sm-12  form-control require">
                            <label class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-form-label" style="font-size:x-small;">
                                <a href="#" onclick="changeForm('/users/forget')">
                                    忘記密碼？
                                </a>
                            </label>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 tm-btn-center">
                                <button type="submit" class="btn btn-small btn-primary d-inline-block mx-auto">登入</button>
                                <button type="button" class="btn btn-small btn-danger d-inline-block mx-auto" onclick="changeForm('/users/create')">註冊</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
