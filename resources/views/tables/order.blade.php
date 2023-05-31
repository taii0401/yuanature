<span class="tm-block-title">
    訂單編號：{{ @$datas["assign_data"]["serial"] }} 
</span>
<br>
<p style="margin-block-start: 0.4em; margin-block-end: 0.4em;">
    <h6>{{ @$datas["assign_data"]["created_at_format"] }}</h6>
</p>
<table class="table table-hover table-bordered table-rwd" style="background-color: #F2FFFF;">
    <thead>    
        <tr class="tr-only-hide text-center">
            <th>訂單狀態</th>
            <th>訂購金額</th>
            <th>訂單備註</th>
            <th>出貨備註</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <span class="td-data-span">訂單狀態：</span>
                <span style="color:{{ @$datas["assign_data"]["status_color"] }}">{{ @$datas["assign_data"]["status_name"] }}</span>
                @if(@$datas["assign_data"]["status"] == "cancel")
                    <span>
                        取消原因：{{ @$datas["assign_data"]["cancel_name"] }}<br>
                        @if(@$datas["assign_data"]["cancel"] == "other" && @$datas["assign_data"]["cancel_remark"] != "")
                            取消備註：{!! @$datas["assign_data"]["cancel_remark_format"] !!}
                        @endif
                    </span>
                @endif
            </td>            
            <td>
                <span class="td-data-span">訂購金額：</span>
                {{ @$datas["assign_data"]["total"] }}元
            </td>
            <td>
                <span class="td-data-span">訂單備註：</span>
                {!! @$datas["assign_data"]["order_remark_format"] !!}
            </td>
            <td>
                <span class="td-data-span">出貨備註：</span>
                {!! @$datas["assign_data"]["delivery_remark_format"] !!}
            </td>
        </tr>
    </tbody>
</table>

<table class="table table-hover table-bordered table-rwd" style="background-color: #FFFFD4;">
    <thead>    
        <tr class="tr-only-hide text-center">
            <th>收件人姓名</th>
            <th>收件人手機</th>
            <th>收件人信箱</th>
            <th>配送方式</th>
            <th>付款方式</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <span class="td-data-span">收件人姓名：</span>
                {{ @$datas["assign_data"]["name"] }}
            </td>
            <td>
                <span class="td-data-span">收件人手機：</span>
                {{ @$datas["assign_data"]["phone"] }}
            </td>
            <td>
                <span class="td-data-span">收件人信箱：</span>
                {{ @$datas["assign_data"]["email"] }}
            </td>
            <td>
                <span class="td-data-span">配送方式：</span>
                <span style="color:{{ @$datas["assign_data"]["delivery_color"] }}">{{ @$datas["assign_data"]["delivery_name"] }}</span>
            </td>
            <td>
                <span class="td-data-span">付款方式：</span>
                <span style="color:{{ @$datas["assign_data"]["payment_color"] }}">{{ @$datas["assign_data"]["payment_name"] }}</span>
            </td>
        </tr>
    </tbody>
</table>