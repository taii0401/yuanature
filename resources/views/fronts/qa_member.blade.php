@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('banner_menu_txt') 常見問題 > {{ @$title_txt }} @endsection
@section('content')
<div class="row">
    <div class="content-info col-xl-7 col-lg-7 col-md-12 col-sm-12">
        <h4 class="mb-4">｜會員問題｜</h4>
        <div class="qa">
            <p class="mb-4">
                <span class="qa-q">Q：忘記密碼怎麼辦？</span><br/>
                <span class="qa-a">A：請至會員登入處點選「忘記密碼」進行驗證及重新設定，如果擔心會經常忘記，也可以使用FACEBOOK或LINE帳戶登入綁定。</span>
            </p>
        </div>
        <div class="qa">
            <p class="mb-4">
                <span class="qa-q">Q：會員資料一定要完整填寫嗎？</span><br/>
                <span class="qa-a">A：會員僅需填寫基本的聯繫資料即可，但完整的填寫將可以幫助我們為您提供更多的優惠訊息，未來不定期推出優惠活動才能緊緊跟上喔！</span>
            </p>
        </div>
        <div class="qa">
            <p class="mb-4">
                <span class="qa-q">Q：忘記註冊時的E-Mail怎麼辦?</span><br/>
                <span class="qa-a">A：可與客服人員聯繫處理。</span>
            </p>
        </div>
    </div>
</div>
@endsection