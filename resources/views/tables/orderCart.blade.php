<form id="form_data_cart" class="tm-signup-form" method="post">
    @csrf
    <input type="hidden" id="action_type" name="action_type" value="delete">
    <input type="hidden" id="product_id" name="product_id" value="">
    <input type="hidden" id="amount" name="amount" value="">
</form>

<table class="table table-hover table-striped table-bordered table-rwd">
    <thead>    
        <tr class="tr-only-hide text-center tm-bg-gray">
            <th>商品名稱</th>
            <th>數量</th>
            <th>售價</th>
            <th>小計</th>
            <!-- 購物車 -->
            <th width="50" style="display: {{ @$datas["assign_data"]["cart_display"]}}"></th>
        </tr>
    </thead>
    <tbody>
        @if(isset($datas["detail_data"]) && !empty($datas["detail_data"]))
            @foreach($datas["detail_data"] as $data) 
            <!-- 訂單 -->
            <tr style="display: {{ @$datas["assign_data"]["order_display"]}}">
                <td>
                    <span class="td-data-span">商品名稱：</span>
                    {{ @$data["name"] }}
                </td>
                <td>
                    <span class="td-data-span">數量：</span>
                    {{ @$data["amount"] }}
                </td>
                <td>
                    <span class="td-data-span">售價：</span>
                    {{ @$data["price"] }}元
                </td>
                <td>
                    <span class="td-data-span">小計：</span>
                    {{ @$data["subtotal"] }}元
                </td>
            </tr>
            <!-- 購物車 -->
            <tr style="display: {{ @$datas["assign_data"]["cart_display"]}}">
                <td>
                    <span class="td-data-span">商品名稱：</span>
                    {{ @$data["name"] }}
                </td>
                <td>
                    <span class="td-data-span">數量：</span>
                    <input type="number" min="1" id="amount_{{ @$data["id"] }}" name="amount[]" value="{{ @$data["amount"] }}" style="width: 50px;" onchange="cartChangeOriginTotal('{{ @$data["id"] }}');cartChangeUserCoupon();">
                </td>
                <td>
                    <span class="td-data-span">售價：</span>
                    <input type="hidden" id="price_{{ @$data["id"] }}" value="{{ @$data["price"] }}">    
                    {{ @$data["price"] }}元
                </td>
                <td>
                    <span class="td-data-span">小計：</span>
                    <input type="hidden" id="subtotal_col_{{ @$data["id"] }}" name="subtotal[]" value="{{ @$data["subtotal"] }}">
                    <span id="subtotal_{{ @$data["id"] }}">{{ @$data["subtotal"] }}</span>元
                </td>
                <td class="text-center">
                    <div class="btn-action">
                        <ul>
                            <li>
                                <i class="fas fa-trash-alt tm-trash-icon btn_submit" onclick="$('#product_id').val('{{ @$data["id"] }}');cartSubmit('delete');"></i>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
<table class="table table-rwd">
    <tr class="tr-total">
        <td align="right">
            <span class="td-total-span">合計：</span>
            <input type="hidden" id="origin_total" name="origin_total" value="{{ @$datas["assign_data"]["origin_total"] }}">
            <span id="origin_total_text">{{ @$datas["assign_data"]["origin_total"] }}</span>元

            <!-- 訂單 -->
            <span style="display: {{ @$datas["assign_data"]["order_display"]}}">
                <br>
                @if(@$datas["assign_data"]["coupon_total"] > 0)
                    <span class="td-total-span">折價：</span>
                    {{ @$datas["assign_data"]["coupon_total"] }}元<br>
                @endif

                <span class="td-total-span">運費：</span>
                {{ @$datas["assign_data"]["delivery_total"] }}元<br>

                <span class="td-total-span">總計：</span>
                {{ @$datas["assign_data"]["total"] }}元
            </span>
        </td>
    </tr>
</table>