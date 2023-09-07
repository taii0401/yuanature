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
                <table class="table table-hover table-striped table-bordered table-rwd">
                    <thead>    
                        <tr class="tr-only-hide text-center">
                            <th>訂單編號</th>
                            <th>訂購日期</th>
                            <th>訂單狀態</th>
                            <th>配送方式</th>
                            <th>付款方式</th>
                            <th>訂購金額</th>
                            <th width="100"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($datas["list_data"]) && !empty($datas["list_data"]))
                            @foreach($datas["list_data"] as $data) 
                            <tr>
                                <td>
                                    <span class="td-data-span">訂單編號：</span>
                                    <a href="#" onclick="changeForm('/orders/detail?orders_uuid={{ @$data["uuid"] }}');"><u>{{ @$data["serial"] }}</u></a>
                                </td>
                                <td>
                                    <span class="td-data-span">訂購日期：</span>
                                    {{ @$data["created_at_format"] }}
                                </td>
                                <td>
                                    <span class="td-data-span">訂單狀態：</span>
                                    <span style="color:{{ @$data["status_color"] }}">{{ @$data["status_name"] }}</span>
                                </td>
                                <td>
                                    <span class="td-data-span">配送方式：</span>
                                    <span style="color:{{ @$data["delivery_color"] }}">{{ @$data["delivery_name"] }}</span>
                                </td>
                                <td>
                                    <span class="td-data-span">付款方式：</span>
                                    <span style="color:{{ @$data["payment_color"] }}">{{ @$data["payment_name"] }}</span>
                                </td>
                                <td>
                                    <span class="td-data-span">訂購金額：</span>
                                    {{ @$data["total"] }}元
                                </td>
                                <td class="text-center">
                                    <div class="btn-action">
                                        <ul>
                                            @if($data["isPay"])
                                                <li>
                                                    <a href="#" target="_blank" onclick="changeForm('/orders/cart_payment?orders_uuid={{ @$data["uuid"] }}');"><i class="fas fa-donate tm-edit-icon" ></i></a>
                                                </li>
                                            @endif
                                            @if($data["isDelete"])
                                                <li>
                                                    <i class="fas fa-trash-alt tm-trash-icon dataModalBtnOrderCancel" data-bs-toggle="modal" data-bs-target="#dataModalOrderCancel" data-id="{{ @$data["uuid"] }},{{ @$data["serial"] }}">
                                                    </i>
                                                </li>
                                            @endif
                                        </ul>
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

@include('forms.orderCancel')
@endsection

@section('script')
<script>
    //取消訂單
    $('.dataModalBtnOrderCancel').click(function () {
        var input_modal_keys = ['uuid','serial'];
        var switch_modal_keys = radio_modal_keys = [];
        setModalInput($(this).data('id'),input_modal_keys,switch_modal_keys,radio_modal_keys);

        $('#serial_text').text($('#input_modal_serial').val());
        $('#input_modal_source').val('user');
    });
</script>
@endsection