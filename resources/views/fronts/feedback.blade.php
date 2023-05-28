@extends('layouts.frontBase')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('banner_menu_txt') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 mx-auto">
        <div class="bg-white tm-block">
            <div class="col-12">
                <div class="row">
                    <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12">
                        <p>
                            歡迎留下您使用過後的想法及建議事項，讓我們能有更多改進的空間，如果您同意本網站使用您的留言及上傳的照片，請為我們勾選同意。有您的支持是我們莫大的榮幸，原生學再次感謝您！
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
                            <img src="{{ @$data["file_data"]["url"] }}" width=170px" height="170px">
                        @endif
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12" style="margin-top:5px;">
                        {{ @$data["name"] }}&nbsp;&nbsp;{{ @$data["age"] }}歲&nbsp;&nbsp;{{ @$data["address_county"] }}{{ @$data["address_district"] }}
                        <p>{!! @$data["content"] !!}</p>
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