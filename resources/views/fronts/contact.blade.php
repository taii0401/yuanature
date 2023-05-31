@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('banner_menu_txt') {{ @$title_txt }} @endsection
@section('content')
<div class="row ">
    <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 mx-auto">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mx-auto">
                <p class="mb-2">
                    謝謝您的主動聯絡<br>
                    原生學 將使用您留下的資訊進行回覆<br>
                    請留下具體的問題及聯絡資訊
                </p>
                <p class="mb-4">
                    我們也歡迎各種合作提案<br>
                    如企業採購、團購、活動邀約等
                </p>
                <p class="mb-4">
                    營業時間：09:00 ~ 18:00（例假日休）<br>
                    如有即時客服需求<br>
                    建議透過LINE聯繫我們<br>
                    Line: @vvb4242s
                </p>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mx-auto tm-login">
                <div class="bg-white tm-block">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h5 class="mt-3">{{ @$title_txt }}</h5>
                        </div>
                    </div>
                
                    <div class="row">
                        <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
                        <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
                        <div class="col-12">
                            <form id="form_data" class="tm-signup-form" method="post">
                                @csrf
                                <input type="hidden" id="action_type" name="action_type" value="add">
                                <div class="row m-t-10" >
                                    <div class="col-12">
                                        <label>姓名</label>
                                        <input type="text" id="name" name="name" class="form-control require" value="">
                                    </div>
                                    <div class="col-12">
                                        <label>電子郵件</label>
                                        <input type="text" id="email" name="email" class="form-control" value="">
                                    </div>
                                    <div class="col-12">
                                        <label>聯絡電話</label>
                                        <input type="text" id="phone" name="phone" class="form-control require" value="">                  
                                    </div>
                                </div>
                                <div class="row m-t-10">
                                    <div class="col-12">
                                        <label class="col-form-label">問題類型</label>
                                        <select class="custom-select col-12" id="type" name="type">
                                            @if(isset($contact_type) && !empty($contact_type))
                                                @foreach($contact_type as $key => $val)
                                                    <option value="{{ $key }}">{{ $val }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="row m-t-10" style="margin-top:20px;">
                                    <div class="col-12">
                                        <label>訊息內容</label>
                                        <textarea id="content" name="content" rows="5" class="form-control require"></textarea>
                                    </div>
                                </div>
                                <div class="row input-group mt-3">
                                    <div class="col-12 tm-btn-center">
                                        <button type="button" class="btn btn-primary btn_submit" onclick="contactSubmit('add');">送出</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
