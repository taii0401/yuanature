@extends('backend.base')
@section('title') {{ @$title_txt }} @endsection
@section('content')
<div class="row">
    <div class="col-12 mx-auto tm-login-col">
        <div class="bg-white tm-block">
            <div class="row">
                <div class="col-12 text-center">
                    <h5 class="mt-3">{{ @$title_txt }}</h5>
                </div>
            </div>
            <div class="row mt-2">
                @if($errors->any())
                    @foreach($errors->all() as $message)
                        <div id="msg_error" class="col-12 alert alert-danger" role="alert">{{ $message }}</div>
                    @endforeach
                @endif
                <div class="col-12">
                    <form id="form_data" method="post" class="tm-login-form" action="{{ route('admin.login') }}">
                        @csrf
                        <div class="input-group">
                            <label class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-form-label">帳號</label>
                            <input type="text" id="account" name="account" class="col-xl-10 col-lg-10 col-md-10 col-sm-12 form-control require">
                        </div>
                        <div class="input-group mt-3">
                            <label class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-form-label">密碼</label>
                            <input type="password" id="password" name="password" class="col-xl-10 col-lg-10 col-md-10 col-sm-12  form-control require">
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 tm-btn-center">
                                <button type="submit" class="btn btn-small btn-primary d-inline-block mx-auto">登入</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection