<table class="table table-hover table-striped tm-table-striped-even mt-3"  style="vertical-align: middle;">
    <thead>
        <tr>
            <th class="text-center tm-bg-gray" height="50px">訂單編號：</th>
            <th style="color:#007bff">{{ @$datas["assign_data"]["serial"] }}</th>
        </tr>
        <tr>
            <th class="text-center tm-bg-gray" height="50px">訂購日期：</th>
            <th>{{ @$datas["assign_data"]["created_at_format"] }}</th>
        </tr>
        <tr>
            <th class="text-center tm-bg-gray" height="50px">收件人姓名：</th>
            <th>{{ @$datas["assign_data"]["name"] }}</th>
        </tr>
        <tr>
            <th class="text-center tm-bg-gray" height="50px">收件人手機：</th>
            <th>{{ @$datas["assign_data"]["phone"] }}</th>
        </tr>
        <tr>
            <th class="text-center tm-bg-gray" height="50px">收件人信箱：</th>
            <th>{{ @$datas["assign_data"]["email"] }}</th>
        </tr>
        @if(@$datas["assign_data"]["delivery"] == "home" && @$datas["assign_data"]["address"] != "")
            <tr>
                <th class="text-center tm-bg-gray" height="50px">收件人地址：</th>
                <th>
                {{ @$datas["assign_data"]["address_zip"] }} {{ @$datas["assign_data"]["address_county"] }}{{ @$datas["assign_data"]["address_district"] }}{{ @$datas["assign_data"]["address"] }}
                </th>
            </tr>
        @endif
        <tr>
            <th class="text-center tm-bg-gray" height="50px">訂單狀態：</th>
            <th style="color:{{ @$datas["assign_data"]["status_color"] }}">{{ @$datas["assign_data"]["status_name"] }}</th>
        </tr>
        @if(@$datas["assign_data"]["status"] == "cancel")
            <tr>
                <th class="text-center tm-bg-gray" height="50px">取消原因：</th>
                <th>{{ @$datas["assign_data"]["cancel_name"] }}</th>
            </tr>
        @endif
        <tr>
            <th class="text-center tm-bg-gray" height="50px">配送方式：</th>
            <th>
                <span style="color:{{ @$datas["assign_data"]["delivery_color"] }}">
                    {{ @$datas["assign_data"]["delivery_name"] }}
                </span>
                <br>
                {{ @$datas["assign_data"]["address_format"] }}
            </th>
        </tr>
        <tr>
            <th class="text-center tm-bg-gray" height="50px">付款方式：</th>
            <th style="color:{{ @$datas["assign_data"]["payment_color"] }}">{{ @$datas["assign_data"]["payment_name"] }}</th>
        </tr>
        <tr>
            <th class="text-center tm-bg-gray" height="50px">訂購金額：</th>
            <th>{{ @$datas["assign_data"]["total"] }}元</th>
        </tr>
        <tr>
            <th class="text-center tm-bg-gray" height="50px">訂單備註：</th>
            <th>{!! @$datas["assign_data"]["order_remark_format"] !!}</th>
        </tr>
        @if(@$datas["assign_data"]["status"] == "cancel" && @$datas["assign_data"]["cancel"] == "other" && @$datas["assign_data"]["cancel_remark"] != "")
            <tr>
                <th class="text-center tm-bg-gray" height="50px">取消備註：</th>
                <th>{!! @$datas["assign_data"]["cancel_remark_format"] !!}</th>
            </tr>
        @endif
        <tr>
            <th class="text-center tm-bg-gray" height="50px">出貨備註：</th>
            <th>{!! @$datas["assign_data"]["delivery_remark_format"] !!}</th>
        </tr>
    </thead>
</table>