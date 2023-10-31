<div class="header-outs">
    <div class="head-wl">
        <div class="search-w3ls"></div>
        <div class="clearfix"> </div>
    </div>
</div>
<div class="div_navbar">
    <div class="row">
        <div class="col-12 div_navbar_login">
        @if(UserAuth::isLoggedIn())
            <a href="/users/edit">
                <img src="{{ asset('img/icons/member.png') }}" height="20px">&nbsp;&nbsp;會員中心
            </a>
            <a href="/users/logout">
                / 登出
            </a>
        @else
            <a href="/users/">
                <img src="{{ asset('img/icons/member.png') }}" height="20px">&nbsp;&nbsp;登入
            </a>
            <a href="/users/create">
                /&nbsp;註冊 
            </a>
        @endif
            &nbsp;&nbsp;
            <a href="/orders/cart">
                <img src="{{ asset('img/icons/cart.png') }}" height="20px">&nbsp;&nbsp;購物車
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div style="width:100px;margin:0 auto">
                <a class="nav-link" href="/">
                    <img src="{{ asset('img/icons/logo.png') }}" height="80px">
                </a>
            </div>
        </div>
        <div class="col-2"></div>
    </div>
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <nav class="navbar navbar-expand-sm navbar-light" style="margin-top:0px;">
                <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto h6">
                        <li class="nav-item">
                            <a class="nav-link" href="/about">關於我們</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/product">購買商品</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">購物指南</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="/shopping">購物須知</a>
                                <a class="dropdown-item" href="/shipment">運送政策</a>
                                <a class="dropdown-item" href="/refunds">退換貨政策</a>
                                <a class="dropdown-item" href="/privacy">隱私權政策</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">常見問題</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="/qa_shopping">購物問題</a>
                                <a class="dropdown-item" href="/qa_product">產品問題</a>
                                <a class="dropdown-item" href="/qa_member">會員問題</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/feedback">使用者回饋</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="col-2"></div>
    </div>
</div>