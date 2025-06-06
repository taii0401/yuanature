@extends('layouts.frontBase')
@section('title') {{ @$assign_data["title_txt"] }} @endsection
@section('banner_menu_txt') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="row tm-mt-big">
    <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 mx-auto">
        <h4 class="mb-4">網頁跳轉中...</h4>
    </div>
</div>
<form id="form_data" name="Newebpay" method="post" action="{{ $assign_data["MpgAction"] }}">
    <input type="hidden" name="MerchantID" value="{{ $assign_data["MerchantID"] }}"><br>
    <input type="hidden" name="TradeInfo" value="{{ $assign_data["tradeInfo"] }}"><br>
    <input type="hidden" name="TradeSha" value="{{ $assign_data["tradeSha"] }}"><br>
    <input type="hidden" name="Version" value="{{ $assign_data["Version"] }}"><br>
    <input type="submit" value="Submit" style="display:none;">
</form>
@endsection

@section('script')
<script>
    $(function () {
        document.getElementById("form_data").submit();
    });
</script>
@endsection