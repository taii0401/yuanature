@extends('layouts.front_base')
@section('title') {{ @$assign_data["title_txt"] }} @endsection
@section('content')
<div class="contact" id="contact">
    <div class="container">
        <h3 class="title clr">{{ @$assign_data["title_txt"] }}</h3>
        <div class="contact-two-grids">
            <div class="col-md-8 contact-left-grid">
                <div class=" col-md-4 col-sm-5 contact-icons">
                    <div class=" footer_grid_left">
                        <div class="icon_grid_left">
                            <span class="fa fa-map-marker" aria-hidden="true"></span>
                        </div>
                        <h5>Address</h5>
                        <p>Unterberg 11,06108,<span>Halle,Germany</span></p>
                    </div>
                    <div class=" footer_grid_left">

                        <div class="icon_grid_left">
                            <span class="fa fa-volume-control-phone" aria-hidden="true"></span>
                        </div>
                        <h5> Contact Us</h5>
                        <p>+(000) 123 4565 32 <span>+(010) 123 4565 35</span></p>
                    </div>
                    <div class=" footer_grid_left">
                        <div class="icon_grid_left">

                            <span class="fa fa-envelope" aria-hidden="true"></span>
                        </div>
                        <h5>Email Us</h5>
                        <p><a href="mailto:info@example.com">info@example1.com</a>
                            <span><a href="mailto:info@example.com">info@example2.com</a></span></p>
                    </div>

                </div>
                <div class="col-md-8 col-sm-7 contact-us">
                    <form action="#" method="post">
                        <div class="styled-input">

                            <input type="text" name="Name" placeholder="Name" required="">

                        </div>
                        <div class="styled-input">

                            <input type="email" name="Email" placeholder="Email" required="">



                        </div>
                        <div class="styled-input">

                            <input type="text" name="phone" placeholder="phone" required="">



                        </div>
                        <div class="styled-input">

                            <textarea name="Message" placeholder="Message" required=""></textarea>



                        </div>
                        <div>
                            <div class="click">
                                <input type="submit" value="SEND">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="clearfix"> </div>
            </div>
            <div class="clearfix"> </div>
        </div>


    </div>
</div>
<!--<div class="row tm-mt-big">
    <div class="col-12 mx-auto tm-login-col">
        <div class="bg-white tm-block">
            <div class="row">
                <div class="col-12 text-center">
                    <i class="fas fa-3x fa-tachometer-alt tm-site-icon text-center"></i>
                    <h2 class="tm-block-title mt-3">登入</h2>
                </div>
            </div>
            <div class="row mt-2">
                @if($errors->any())
                    @foreach($errors->all() as $message)
                        <div id="msg_error" class="col-12 alert alert-danger" role="alert">{{ $message }}</div>
                    @endforeach
                @endif
                <div class="col-12">
                    <form id="form_data" method="post" class="tm-login-form" action="">
                        @csrf
                        <div class="input-group">
                            <label for="username" class="col-xl-2 col-lg-2 col-md-2 col-sm-5 col-form-label">帳號</label>
                            <input type="email" id="username" name="username" class="form-control require" placeholder="電子郵件">
                        </div>
                        <div class="input-group mt-3">
                            <label for="password" class="col-xl-2 col-lg-2 col-md-2 col-sm-5 col-form-label">密碼</label>
                            <input type="password" id="password" name="password" class="form-control require">
                        </div>
                        <div class="input-group mt-3">
                            <div class="col-2"></div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-primary d-inline-block mx-auto">登入</button>
                            </div>
                            <div class="col-4">
                                <button type="button" class="btn btn-primary d-inline-block mx-auto" onclick="changeForm('/users/forget')">忘記密碼</button>
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-primary d-inline-block mx-auto" onclick="changeForm('/users/create')">註冊</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>-->
@endsection