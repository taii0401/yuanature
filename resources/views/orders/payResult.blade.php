@extends('layouts.frontBase')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="row tm-content-row tm-mt-big">
    <div class="col-xl-12 col-lg-12 tm-md-12 tm-sm-12 tm-col">
        <div class="bg-white tm-block h-100">
            <div class="row">
                <div class="col-12">
                    <h2 class="tm-block-title">{{ @$datas["assign_data"]["title_txt"] }}</h2>
                </div>
            </div>
            <div class="table-responsive">
                <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:{{ @$datas["assign_data"]["danger_none"] }};">交易失敗</div>
                <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:{{ @$datas["assign_data"]["success_none"] }};">交易成功</div>
                @include('tables.order')
            </div>
            <div class="row">
                <div class="col-12 col-sm-6"></div>
                <div class="col-12 col-sm-6 tm-btn-right"></div>
            </div>
        </div>
    </div>
</div>
@endsection