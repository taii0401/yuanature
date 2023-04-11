@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('content')
<div class="content">
    @for($i = 1;$i <= 15;$i++)
    <img src="../../img/product/product_{{ $i }}.jpg" width="100%">
    @endfor
</div>
@endsection