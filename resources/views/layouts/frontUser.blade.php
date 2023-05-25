<div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 mx-auto">
    <div>
        <div class="row">
            <div class="col-xl-3 col-lg-12">
                
            </div>
            <div class="col-xl-9 col-lg-12 col-md-12 col-sm-12 user_menu">
                <h5 style="text-align: center;">會員中心</h5>
                <ul>
                    <li><a href="/users/edit">會員資料</a></li>
                    @if(UserAuth::userdata()->register_type == "email")
                        <li><a href="/users/edit_password">修改密碼</a></li>
                    @endif
                    <li><a href="/orders">訂單查詢</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>