@extends('layouts.front_base')
@section('title') {{ @$assign_data["title_txt"] }} @endsection
@section('content')
<div class="content">
    <div class="row tm-mt-big">
        <div class="col-12 mx-auto tm-login-col">
            <div class="bg-white tm-block">
                <div class="row">
                    <div class="col-12 text-center">
                        <i class="fas fa-3x fa-tachometer-alt tm-site-icon text-center"></i>
                        <h2 class="tm-block-title mt-3">登入</h2>
                    </div>
                </div>
                <div class="row mt-2">
                    @if($errors->any())
                        @foreach($errors->all() as $message)
                            <div id="msg_error" class="col-12 alert alert-danger" role="alert">{{ $message }}</div>
                        @endforeach
                    @endif
                    <div class="col-12">
                        <form id="form_data" method="post" class="tm-login-form" action="">
                            @csrf
                            <div class="input-group mt-3" style="text-align:center;">
                                <div class="col-6">
                                    <button type="button" class="btn d-inline-block mx-auto" style="border:0px;background-color:transparent;padding:0px;text-align:center" onclick="changeForm('/users/forget')">
                                        <img src="../../img/icons/fb_login.gif" width="120px" height="50px">
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn d-inline-block mx-auto" style="border:0px;background-color:transparent;padding:0px;text-align:center" onclick="changeForm('/users/forget')">
                                        <img src="../../img/icons/line_login.gif" width="120px" height="50px">
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <div class="input-group">
                                <label class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-form-label">帳號</label>
                                <input type="email" id="username" name="username" class="col-xl-7 col-lg-7 col-md-7 col-sm-12 form-control require" placeholder="電子郵件">
                            </div>
                            <div class="input-group mt-3">
                                <label class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-form-label">密碼</label>
                                <input type="password" id="password" name="password" class="col-xl-7 col-lg-7 col-md-7 col-sm-12  form-control require">
                                <label class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-form-label" style="font-size:x-small;">
                                    <a href="/users/forget">
                                        忘記密碼？
                                    </a>
                                </label>
                            </div>
                            <div class="row mt-3">
                                <div class="col-xl-2 col-lg-2 col-md-2"></div>
                                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12  tm-btn-center">
                                    <button type="submit" class="btn btn-small btn-primary d-inline-block mx-auto">登入</button>
                                    <button type="button" class="btn btn-small btn-primary d-inline-block mx-auto" onclick="changeForm('/users/create')">註冊</button>
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


<?php /*

<div class="container"> 
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3"></div>
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 contact-two-grids">
        <div class="contact-left-grid">
            <table class="table-form" style="width: 100%;">
                <tr>
                    <td colspan="2">
                        <h3 class="title clr">{{ @$assign_data["title_txt"] }}</h3>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <button type="button" class="btn d-inline-block mx-auto" style="border:0px;background-color:transparent;padding:0px;text-align:center" onclick="changeForm('/users/forget')">
                            <img src="../../img/icons/fb_login.gif" width="80px" height="50px">
                        </button>
                    </td>
                    <td align="center">
                        <button type="button" class="btn d-inline-block mx-auto" style="border:0px;background-color:transparent;padding:0px;text-align:center" onclick="changeForm('/users/forget')">
                            <img src="../../img/icons/line_login.gif" width="80px" height="50px">
                        </button>
                    </td>
                </tr>
            </table>
            <hr>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <form id="form_data" method="post" class="tm-login-form" action="">
                    @csrf
                    <table class="table-form" style="width: 100%;">
                        <tr>
                            <td width="20%">
                                <label for="username" class="col-form-label">帳號</label>
                            </td>
                            <td>
                                <input type="email" id="username" name="username" class="form-control require" placeholder="電子郵件">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="password" class="col-form-label">密碼</label>
                            </td>
                            <td>
                                <input type="password" id="password" name="password" class="form-control require">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <button type="submit" class="btn btn-primary d-inline-block mx-auto">登入</button>
                                <button type="button" class="btn btn-danger d-inline-block mx-auto" onclick="changeForm('/users/forget')">忘記密碼</button>
                                <button type="button" class="btn btn-primary d-inline-block mx-auto" onclick="changeForm('/users/create')">註冊</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection*/?>
