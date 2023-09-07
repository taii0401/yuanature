@extends('layouts.frontBase')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('banner_menu_txt') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="row tm-mt-big">
    <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 mx-auto">
        <h4 class="mb-4">網頁跳轉中...</h4>
    </div>
</div>
<form id="form_post_data" name="form_post_data" method="post" action="{{ @$datas["assign_data"]["action"] }}">
    @if(isset($datas["post_data"]))
        @foreach($datas["post_data"] as $post_key => $post_val)
            <input type="hidden" name="{{ @$post_key }}" value="{{ @$post_val }}">
        @endforeach
    @endif
    <input type="submit" value="Submit" style="display:none;">
</form>
@endsection

@section('script')
<script>
    $(function () {
        document.getElementById("form_post_data").submit();
    });
</script>
@endsection