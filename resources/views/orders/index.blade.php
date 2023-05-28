@extends('layouts.frontBase')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('banner_menu_txt') 會員中心 > {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="row tm-mt-big">
    @include('layouts.frontUser')
    <div class="col-xl-10 col-lg-10 col-md-10 col-sm-12 mx-auto tm-login">
        <div class="bg-white tm-block">
            <div class="row">
                <div class="form-group col-md-4 col-sm-12">
                    <div class="input-group">
                        <input type="text" id="keywords" name="keywords" class="form-control search_input_data" placeholder="訂單編號" value="{{ @$datas["assign_data"]["keywords"] }}">
                        <span class="input-group-btn">
                            <button class="btn btn-secondary" onclick="getSearchUrl('{{ @$datas["assign_data"]["search_link"] }}');"><i class="fas fa-search"></i></button>
                        </span>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <input type="hidden" id="orderby" name="orderby" class="form-control search_input_data" value="{{ @$datas["assign_data"]["orderby"] }}">

                    @if(isset($datas["option_data"]) && !empty($datas["option_data"]))
                        @foreach($datas["option_data"] as $option_key => $option_val)
                            <div class="dropdown btn-group">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ @$option_val["name"]}}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="background-color: #e9ecef;">
                                    @if(!empty($option_val["data"]))    
                                        @foreach($option_val["data"] as $key => $val) 
                                        <a class="dropdown-item @if($datas["assign_data"][$option_key] == $key) active @endif" href="#" onclick="$('#{{ @$option_key }}').val('{{ @$key }}');getSearchUrl('{{ @$datas["assign_data"]["search_link"] }}');">{{ @$val }}</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="tm-table-mt tm-table-actions-row">
                <div class="tm-table-actions-col-left">
                    
                </div>
                <div class="tm-table-actions-col-right">
                    @include('layouts.page')
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped tm-table-striped-even mt-3"  style="vertical-align: middle;">
                    <thead>
                        <tr class="tm-bg-gray">
                            <th scope="col" class="text-center" height="50px">訂單編號</th>
                            <th scope="col" class="text-center" style="width:150px;">訂購日期</th>
                            <th scope="col" class="text-center" style="width:100px;">訂單狀態</th>
                            <!--<th scope="col" class="text-center" style="width:12%;">配送方式</th>
                            <th scope="col" class="text-center" style="width:12%;">付款方式</th>-->
                            <th scope="col" class="text-center" style="width:100px;">訂購金額</th>
                            <th scope="col" style="width:45px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($datas["list_data"]) && !empty($datas["list_data"]))
                            @foreach($datas["list_data"] as $data)  
                            <tr>
                                <td class="text-center" height="50px">
                                    <a href="#" onclick="changeForm('/orders/detail?orders_uuid={{ @$data["uuid"] }}');">{{ @$data["serial"] }}</a>
                                </td>
                                <td class="text-center">{{ @$data["created_at_format"] }}</td>
                                <td class="text-center" style="color:{{ @$data["status_color"] }}">{{ @$data["status_name"] }}</td>
                                <!--<td class="text-center" style="color:{{ @$data["delivery_color"] }}">{{ @$data["delivery_name"] }}</td>
                                <td class="text-center" style="color:{{ @$data["payment_color"] }}">{{ @$data["payment_name"] }}</td>-->
                                <td class="text-center">{{ @$data["total"] }}元</td>
                                <td>
                                    <div class="col-12">
                                        @if($data["status"] == "nopaid")
                                            <!--<div class="btn-action">
                                                <i class="fas fa-edit tm-edit-icon dataModalOrderPayBtn" data-bs-toggle="modal" data-bs-target="#dataModalOrderPay" data-id="{{ @$data["uuid"] }},{{ @$data["serial"] }},{{ @$data["payment"] }},{{ @$data["delivery"] }},{{ @$data["address_zip"] }},{{ @$data["address"] }}">
                                                </i>
                                            </div>-->
                                            <div class="btn-action">
                                                <i class="fas fa-trash-alt tm-trash-icon dataModalOrderCancelBtn" data-bs-toggle="modal" data-bs-target="#dataModalOrderCancel" data-id="{{ @$data["uuid"] }},{{ @$data["serial"] }}">
                                                </i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="tm-table-mt tm-table-actions-row">
                <div class="tm-table-actions-col-left">
                    
                </div>
                <div class="tm-table-actions-col-right">
                    @include('layouts.page')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('forms.orderCancel')
@include('forms.orderPay')

@section('script')
<script>
    //訂單付款
    $('.dataModalOrderPayBtn').click(function () {
        var input_modal_keys = ['pay_uuid','pay_serial','payment','delivery','address_zip','address'];
        var switch_modal_keys = [];
        var radio_modal_keys = ['payment','delivery'];
        setModalInput($(this).data('id'),input_modal_keys,switch_modal_keys,radio_modal_keys);

        $('#pay_serial_text').text($('#input_modal_pay_serial').val());
        $('#input_modal_pay_source').val('user');

        //配送方式-宅配配送顯示收件人地址
        changeDataDisplay('checked','delivery','input_modal_address','home',true);
        //縣市、鄉鎮市區、郵遞區號
        const twzipcode = new TWzipcode();
        twzipcode.set($('#input_modal_address_zip').val());
    });

    //取消訂單
    $('.dataModalOrderCancelBtn').click(function () {
        var input_modal_keys = ['uuid','serial'];
        var switch_modal_keys = radio_modal_keys = [];
        setModalInput($(this).data('id'),input_modal_keys,switch_modal_keys,radio_modal_keys);

        $('#serial_text').text($('#input_modal_serial').val());
        $('#input_modal_source').val('user');
    });
</script>
@endsection