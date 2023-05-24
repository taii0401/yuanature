@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('banner_menu_txt') 購物指南 > {{ @$title_txt }} @endsection
@section('content')
<div class="row">
    <div class="content-info col-xl-7 col-lg-7 col-md-12 col-sm-12">
        <h4 class="mb-4">｜首次購物｜</h4>
        <ul style="list-style:none">
            <li>請先註冊您的專屬會員帳號，支持Facebook或LINE註冊，或使用E-mail註冊即可。</li>
            <li class="primary">首次註冊將獲得購物金$100，單筆訂單滿$1,000可使用，期限為1年。</li>
            <li class="primary">部分優惠活動恕不適用會員「購物金」或「優惠券」，請依活動公布之優惠訊息為主。</li>
        </ul>
    </div>
    <div class="clearfix"> </div>
</div>
@endsection