<table class="table table-hover table-bordered table-rwd" style="background-color: #FFFFD4;">
    <thead>    
        <tr class="tr-only-hide text-center">
            <th>收件人姓名</th>
            <th>收件人手機</th>
            <th>收件人信箱</th>
            <th>配送方式</th>
            <!-- 訂單明細 -->
            <th style="display: {{ @$datas["assign_data"]["order_detail_display"]}}">付款方式</th>
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
                <br>{!! @$datas["assign_data"]["address_format"] !!}
            </td>
            <!-- 訂單明細 -->
            <td style="display: {{ @$datas["assign_data"]["order_detail_display"]}}">
                <span class="td-data-span">付款方式：</span>
                <span style="color:{{ @$datas["assign_data"]["payment_color"] }}">{{ @$datas["assign_data"]["payment_name"] }}</span>
            </td>
        </tr>
    </tbody>
</table>