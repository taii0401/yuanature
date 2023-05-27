@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('banner_menu_txt') {{ @$title_txt }} @endsection
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
    </div>
</div>
@endsection