@extends('layouts.frontBase')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('banner_menu_txt') 會員中心 > 訂單查詢 > {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="row tm-mt-big">
    @include('layouts.frontUser')
    <div class="col-xl-10 col-lg-10 col-md-10 col-sm-12 mx-auto tm-login">
        <div class="bg-white tm-block">
            <div class="col-12 text-right" style="margin:10px 0;">
                <button type="button" class="btn btn-primary" onclick="changeForm('/orders')">返回</button>
            </div>
            <div class="table-responsive" style="margin-top: 10px;">
                @include('tables.order')
                @include('tables.orderUser')
            </div>
            <div class="table-responsive">
                @include('tables.orderCart')
            </div>
            <div class="row">
                <div class="col-12 col-sm-6"></div>
                <div class="col-12 col-sm-6 tm-btn-right">
                    <button type="button" class="btn btn-primary" onclick="changeForm('/orders')">返回</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(function () {
        //訂單狀態-已取消顯示取消原因
        changeDataDisplay('checked','status','input_modal_cancel','cancel',true);
        //配送方式-宅配配送顯示收件人地址
        changeDataDisplay('checked','delivery','input_modal_address','home',true);
    });

</script>
@endsection