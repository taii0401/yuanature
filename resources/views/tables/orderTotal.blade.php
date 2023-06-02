<table class="table table-bordered table-rwd" style="background-color: #F2FFFF;">
    <thead>    
        <tr class="tr-only-hide text-center">
            <th>商品金額</th>
            <th>折價金額</th>
            <th>運費</th>
            <th>總金額</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <span class="td-data-span">商品金額：</span>
                <input type="hidden" id="product_total" name="product_total" value="{{ @$datas["assign_data"]["product_total"] }}">
                {{ @$datas["assign_data"]["product_total"] }}元
            </td>            
            <td>
                <span class="td-data-span">折價金額：</span>
                <input type="hidden" id="coupon_total" name="coupon_total" value="{{ @$datas["assign_data"]["coupon_total"] }}">
                {{ @$datas["assign_data"]["coupon_total"] }}元
            </td>
            <td>
                <span class="td-data-span">運費：</span>
                <input type="hidden" id="delivery_total" name="delivery_total" value="{{ @$datas["assign_data"]["delivery_total"] }}">
                <span id="delivery_total_text">{{ @$datas["assign_data"]["delivery_total"] }}</span>元
            </td>
            <td>
                <span class="td-data-span">總金額：</span>
                <input type="hidden" id="total" name="total" value="">
                <span id="total_text">{{ @$datas["assign_data"]["total"] }}</span>元
            </td>
        </tr>
    </tbody>
</table>