@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('content')
<div class="content">
    <div class="row tm-mt-big">
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 content-bg">
            <div class="row">
                <div class="col-12 text-center">
                    <h6 class="mt-3">{{ @$text }}</h6>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 tm-btn-center">
                    <button type="button" class="btn btn-small btn-primary d-inline-block mx-auto" onclick="changeForm('{{ @$button_url }}')">{{ @$button_txt }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
