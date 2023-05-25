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
            <li><img src="{{ asset('img/shopping.jpg') }}" width="100%"></li>
        </ul>
        <ul>
            <li>完成訂購程序後，系統將發送確認信至您的會員信箱，如未收到請查看垃圾郵件。</li>
            <li><span class="star">送出訂單後須於7天內完成付款，逾期訂單將被取消。</span></li>
            <li>如想追蹤訂單處理進度，您可於官網右上角點選「會員中心」>「訂單查詢」查看。</li>
        </ul>
        <h4 class="mb-4">｜付款方式｜</h4>
        <ul>
            <li>線上刷卡： 提供 VISA / MASTER / JCB 信用卡，一次性付款。</li>
            <li>ATM 櫃員機：訂單成立後系統會提供一組 「 匯款帳號 」，請於三日內完成匯款，若超過三日則需再重新下單領取新的匯款帳號。</li>
            <li>LINE Pay：可使用line point。</li>
            <li class="primary">貼心提醒：當「逾時未付款」或「付款失敗」時，系統會自動取消訂單，並補回所使用之購物金等優惠折扣，需再次重新下單。</li>
        </ul>
        <h4 class="mb-4">｜發票說明｜</h4>
        <ul>
            <li>本站使用電子發票，在收到款項後，系統將寄送電子發票開立通知至您的聯絡信箱。</li>
            <li>請確認選擇所需的發票類型（個人或公司統編），發票一經開立，不得要求更改。</li>
            <li>若發票中獎會以電子郵件通知您，您可以至7-11超商門市ibon機台列印。</li>
        </ul>
    </div>
    <div class="clearfix"> </div>
</div>
@endsection