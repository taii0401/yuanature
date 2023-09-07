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
                        <br>取消原因：{{ @$datas["assign_data"]["cancel_name"] }}<br>
                        @if(@$datas["assign_data"]["cancel_remark"] != "")
                            取消備註：{!! @$datas["assign_data"]["cancel_remark_format"] !!}
                        @endif
                    </span>
                @endif
                @if($datas["assign_data"]["status"] == "nopaid" && $datas["assign_data"]["pay_time"] == "")
                    <span style="color:red">
                        <br>請於{{ @$datas["assign_data"]["expire_time"] }}前付款
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