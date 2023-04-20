@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('content')
<div class="content">
    <div style="position:fixed;z-index:1;top:50%;right:5%;">
        <form id="form_data" class="tm-signup-form" method="post">
            @csrf
            <input type="hidden" id="action_type" name="action_type" value="add">
            <input type="hidden" id="product_id" name="product_id" value="1">
            <input type="hidden" id="amount" name="amount" value="1">
        </form>
        <a href="#" onclick="cartSubmit('add')">
            <img alt="加入購物車" src="{{ asset('img/icons/cart.jpg') }}" width="50px">
        </a>
    </div>
    @for($i = 1;$i <= 15;$i++)
        <img src="../../img/product/product_{{ $i }}.jpg" width="100%">
    @endfor
</div>
@endsection
