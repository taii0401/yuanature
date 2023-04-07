<div class="header-outs">
    <div class="head-wl">
        <div class="search-w3ls">
        @if(UserAuth::isLoggedIn())
            <a href="/users/edit">
                <img src="{{ asset('img/icons/member.png') }}" height="20px">&nbsp;&nbsp;{{ UserAuth::userdata()->name }}
            </a>
            <a href="/users/logout">
                / 登出
            </a>
        @else
            <a href="/users/">
                <img src="{{ asset('img/icons/member.png') }}" height="20px">&nbsp;&nbsp;登入 / 註冊
            </a>
        @endif
            &nbsp;&nbsp;
            <a href="/users/cart">
                <img src="{{ asset('img/icons/cart.png') }}" height="20px">&nbsp;&nbsp;購物車
            </a>
        </div>
        <div class="clearfix"> </div>
    </div>
</div>
<div class="row" style="background-color: #bed0c0;">
    <div class="col-12">
        <nav class="navbar navbar-expand-md navbar-light" style="margin-top:0px;">
            <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent" style="background-color: #bed0c0;">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <img src="{{ asset('img/icons/logo.jpg') }}" height="80px">
                        </a>
                    </li>
                    <li class="nav-item" style="margin-top:15px">
                        <a class="nav-link" href="/about">關於我們</a>
                    </li>
                    <li class="nav-item" style="margin-top:15px">
                        <a class="nav-link" href="/product">廣志足白浴露</a>
                    </li>
                    <li class="nav-item" style="margin-top:15px">
                        <a class="nav-link" href="/cart_info">購物指南</a>
                    </li>
                    <li class="nav-item" style="margin-top:15px">
                        <a class="nav-link" href="/question">常見問題</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>