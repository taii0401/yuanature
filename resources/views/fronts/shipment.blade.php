@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('banner_menu_txt') 購物指南 > {{ @$title_txt }} @endsection
@section('content')
<div class="row">
    <div class="content-info col-xl-7 col-lg-7 col-md-10 col-sm-10">
        <h4 class="mb-4">｜出貨說明｜</h4>
        <ul>
            <li>官網適用宅配 (限台灣本島 / 離島)、<span class="star">超商取貨不付款</span> (7-11 / 全家)。</li>
            <li>將依照訂單順序出貨，若訂單皆為現貨商品，需等待<span class="star">3-7</span>個工作天；如遇出貨量較大時，需稍等<span class="star">7-14</span>個工作天。</li>
            <li>寄達時間僅供參考，節日或活動期間倉庫與物流業者訂單量大，可能造成延誤，請見諒。</li>
            <li>如遇商品寄送量較多，訂單將延遲1~2日出貨，實際出貨時間將以您收到的出貨通知 E-mail 為準，請見諒。</li>
            <li class="primary">商品到店時會有簡訊通知，請於到店七天內前往取貨，逾時未取商品將會被退回，訂單將被取消，購物金不返還，並於下次購買時補收逾時未取訂單之運費；視情節我們將限制貨到付款選項並保留出貨與否的權利。</li>
            <li class="primary">如手機有設定阻擋廣告簡訊，會造成收不到取貨通知。</li>
        </ul>
        <h5>&nbsp;&nbsp;台灣本島</h5>
        <ul>
            <li>宅配：(出貨後<span class="star">1-2日</span>到貨)</li>
            <li>超商取貨：7-11、全家超商門市（出貨後<span class="star">2-3日</span>到貨）</li>
        </ul>
        <h5>&nbsp;&nbsp;台灣離島</h5>
        <ul>
            <li>宅配：(出貨後<span class="star">3-5日</span>到貨)</li>
            <li>超商取貨：7-11、全家超商門市（出貨後<span class="star">3-5日</span>到貨）</li>
        </ul>
        <h4 class="mb-4">｜運費說明｜</h4>
        <ul>
            <li>請依配送地址選擇訂單運費，如訂單地址與運費不符將取消訂單退款不出貨，敬請見諒。</li>
            <li>免運門檻計算為優惠折扣及購物金折抵後之單筆訂單金額為準。</li>
            <li>運費（手續費）不列入購物金（點數）回饋及滿額優惠計算。</li>
            <li>使用超商取貨時，送貨資料請務必填寫跟證件相同的真實姓名；請留意到貨簡訊及 E-mail 貨品狀態通知信，並於七天內完成取貨！若因「留錯姓名」或「逾期未取」導致被退件，須補匯重新出貨等費用。</li>
            <li>當商品已出貨，但因您的原因被退回，除須自行負擔物流處理費外，該訂購帳號達2次以上將無法使用此配送方式。</li>
        </ul>
        <h5>&nbsp;&nbsp;台灣本島</h5>
        <ul>
            <li>宅配：<span class="star">NT 100</span>，訂單滿NT 1,500免運費</li>
            <li>超商取貨：<span class="star">NT 70</span>，訂單滿NT 1,500免運費</li>
        </ul>
        <h5>&nbsp;&nbsp;台灣離島</h5>
        <ul>
            <li>宅配：<span class="star">NT 150</span>，訂單滿NT 2,000免運費</li>
            <li>超商取貨：<span class="star">NT 110</span>，訂單滿NT 2,000免運費</li>
        </ul>
    </div>
    <div class="clearfix"> </div>
</div>
@endsection