<div class="row">
    <div class="col-12">
        <nav class="navbar navbar-expand-xl navbar-light bg-light">
            <a class="navbar-brand" href="/">
                <i class="fas fa-3x fa-tachometer-alt tm-site-icon"></i>
                <h1 class="tm-site-title mb-0">UN SHOP後台管理</h1>
            </a>
            <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto">
                    @if(UserAuth::isLoggedIn())
                        <li class="nav-item">
                            <a class="nav-link" href="/products">商品管理</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/fronts/my_page/{{ UserAuth::userdata()->short_link }}">我的頁面</a>
                        </li>
                    @endif
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        @if(UserAuth::isLoggedIn())
                            <a class="nav-link d-flex" href="/users/logout">
                                <i class="far fa-user mr-2 tm-logout-icon"></i>
                                <span>{{ UserAuth::userdata()->name }}  登出</span>
                            </a>
                        @else
                            <a class="nav-link d-flex" href="/users">
                                <i class="far fa-user mr-2 tm-logout-icon"></i>
                                <span>登入</span>
                            </a>
                        @endif
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>