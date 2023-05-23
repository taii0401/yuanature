@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('banner_menu_txt') 常見問題 > {{ @$title_txt }} @endsection
@section('content')
<div class="row">
    <div class="content-info col-xl-7 col-lg-7 col-md-10 col-sm-10">
        <h4 class="mb-4">｜購物問題｜</h4>
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
        <div class="qa">
            <p class="mb-4">
                <span class="qa-q">Q：要如何使用我的購物金或是折價券呢？</span><br/>
                <span class="qa-a">A：購物金及折價券使用方式皆不同，建議詳閱使用說明，逾期述不補發，請注意使用期限。</span>
            </p>
        </div>
        <div class="qa">
            <p class="mb-4">
                <span class="qa-q">Q：超商門市訂單可否延後取貨期限？</span><br/>
                <span class="qa-a">A：超商門市取貨無法延後取貨期限，建議出貨前備註延後出貨時間或於出貨前聯繫客服安排延後處理；出貨後將無法延後取貨，如逾期未取貨將影響您的會員權益，請您多加注意。</span>
            </p>
        </div>
        <div class="qa">
            <p class="mb-4">
                <span class="qa-q">Q：沒有期限內前往超商門市取貨，該怎麼辦？</span><br/>
                <span class="qa-a">A：商品送達門市後會保留7天，如逾期未前往門市領取，商品將會直接退回本公司，訂單亦將被取消，請重新上網訂購；帳號有2次未取貨紀錄者，將暫停該帳號使用超商取貨之權益。</span>
            </p>
        </div>
    </div>
</div>
@endsection