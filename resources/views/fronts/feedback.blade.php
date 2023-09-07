@extends('layouts.frontBase')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('banner_menu_txt') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="row">
    <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 mx-auto">
        <div class="bg-white tm-block">
            <div class="col-12">
                <div class="row">
                    <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12">
                        <p>
                            感謝您對台灣原生品牌「原生學」的支持，歡迎留下您的「使用後感想」及照片，讓更多人可以認識我們。<br>
                            有您的分享與鼓勵，我們會更努力及用心的研發更多好的產品給你們。
                        </p>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12" style="margin-top:20px;">
                        <button type="button" class="btn btn-primary" style="width:100%; height:80px;" onclick="changeForm('/feedback_detail')">點擊留言</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="tm-block">
            <div class="tm-table-mt tm-table-actions-row">
                <div class="tm-table-actions-col-left">
                    
                </div>
                <div class="tm-table-actions-col-right">
                    @include('layouts.page')
                </div>
            </div>

            @if(isset($datas["list_data"]) && !empty($datas["list_data"]))
                @foreach($datas["list_data"] as $data) 
                <div class="row" style="padding:10px;">
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 mx-auto">
                        @if(isset($data["file_data"]["url"]))
                            <img src="{{ @$data["file_data"]["url"] }}" width="150px" height="150px" style="border-radius:50%">
                        @endif
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12" style="margin-top:5px;">
                        {{ @$data["name"] }}&nbsp;&nbsp;
                        <span style="color: red;">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fa fa-heart fa-xs"></i>
                            @endfor
                        </span>
                        <!--{{ @$data["age"] }}&nbsp;&nbsp;{{ @$data["address_county"] }}{{ @$data["address_district"] }}-->
                        <p style="font-size: smaller;">{!! @$data["content"] !!}</p>
                        <br>
                        @if(isset($data["file_used_data"]))
                            @foreach($data["file_used_data"] as $file_used_data)
                                @if(isset($file_used_data["url"]))
                                    <img src="{{ @$file_used_data["url"] }}" width="100px" height="100px">
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                @endforeach
            @endif

            <div class="tm-table-mt tm-table-actions-row">
                <div class="tm-table-actions-col-left">
                    
                </div>
                <div class="tm-table-actions-col-right">
                    @include('layouts.page')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection