@extends('layouts.frontBase')
@section('title') {{ @$assign_data["title_txt"] }} @endsection
@section('content')
<div class="content">
    <div class="row tm-mt-big">
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 content-bg">
            <h4 class="mb-4">產品問題</h4>
            <div class="qa">
                <p class="mb-4">
                    <span class="qa-q">Q：產品標示建議浸泡10 ~ 12分鐘，我可以泡更久嗎？</span><br/>
                    <span class="qa-a">A：每個人的肌膚敏感度不同，建議使用10 ~ 12分鐘即有很好的效果。</span>
                </p>
            </div>
            <div class="qa">
                <p class="mb-4">
                    <span class="qa-q">Q：廣志足白浴露是哪裡製造的？</span><br/>
                    <span class="qa-a">A：我們是台灣原生品牌，從研發到製造都是台灣製造的優質產品。</span>
                </p>
            </div>
            <div class="qa">
                <p class="mb-4">
                    <span class="qa-q">Q：敏感性肌膚及孕婦可否使用？</span><br/>
                    <span class="qa-a">A：當然可以。且經SGS檢驗絕無重金屬殘留，不管是敏感肌膚或是孕婦都可以安心使用。</span>
                </p>
            </div>
            <div class="qa">
                <p class="mb-4">
                    <span class="qa-q">Q：足部有傷口可以使用嗎？</span><br/>
                    <span class="qa-a">A：有開放性傷口的時候不建議使用，請於傷口癒合後再使用本產品。</span>
                </p>
            </div>
            <div class="qa">
                <p class="mb-4">
                    <span class="qa-q">Q：產品保存方式有何限制？</span><br/>
                    <span class="qa-a">A：一般保養品保存方式都不建議放置於太陽直射的地方，因為太陽直射溫度升高可能會讓內容物變質，降低原有效用，所以請放置於陰涼處。</span>
                    <span style="color:red">(注意！是陰涼處，不是冰箱… )</span>
                </p>
            </div>
        </div>
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 content-bg">
            <h4 class="mb-4">會員問題</h4>
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
        </div>
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 content-bg">
            <h4 class="mb-4">購買問題</h4>
            <div class="qa">
                <p class="mb-4">
                    <span class="qa-q">Q：無法正常下單怎麼辦？</span><br/>
                    <span class="qa-a">A：請重新整理頁面後再操作一次，如果依舊無法正常下單訂購，請聯絡線上客服並留下您的會員資料及問題，我們會盡快為您處理。</span>
                </p>
            </div>
            <div class="qa">
                <p class="mb-4">
                    <span class="qa-q">Q：有哪些付款方式？</span><br/>
                    <span class="qa-a">A：目前支持 1.信用卡付款 2.ATM轉帳 3.LINE Pay。</span>
                </p class="mb-4">
            </div>
            <div class="qa">
                <p class="mb-4">
                    <span class="qa-q">Q：有哪些配送方式？</span><br/>
                    <span class="qa-a">A：目前支持 1.超商取貨 2.宅配配送。</span>
                </p>
            </div>
            <div class="qa">
                <p class="mb-4">
                    <span class="qa-q">Q：如何知道已經下單成功？</span><br/>
                    <span class="qa-a">A：下單成功後，系統會發送一封MAIL通知信給您，也可以至會員中心確認您的訂單狀況。</span>
                </p>
            </div>
            <div class="qa">
                <p class="mb-4">
                    <span class="qa-q">Q：如何取消訂單？</span><br/>
                    <span class="qa-a">A：您可於「會員中心」>「訂單查詢」自行取消訂單，如無法線上取消訂單，可能訂單資訊已轉往出貨作業處理，此時請您予線上客服聯絡，我們將盡速為您處理。</span>
                </p>
            </div>
            <div class="qa">
                <p class="mb-4">
                    <span class="qa-q">Q：訂單成立後，是否可以修改訂單內容或是合併訂單運費呢？</span><br/>
                    <span class="qa-a">A：訂單成立後即進入系統後台，無法再行修改或是合併訂單，建議您取消訂單後重新操作一次。</span>
                </p>
            </div>
            <div class="qa">
                <p class="mb-4">
                    <span class="qa-q">Q：訂單的收件地址填錯了，能修改嗎？</span><br/>
                    <span class="qa-a">A：宅配配送地址錯誤時可於出貨前聯絡線上客服協助處理。</span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection