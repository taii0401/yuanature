@extends('backend.base')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="row tm-content-row tm-mt-big">
    <div class="col-xl-12 col-lg-12 tm-md-12 tm-sm-12 tm-col">
        <div class="bg-white tm-block h-100">
            <div class="row">
                <div class="col-12">
                    <h2 class="tm-block-title">{{ @$datas["assign_data"]["title_txt"] }}</h2>
                </div>
            </div>
            <div class="table-responsive">
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
                                <th>{{ @$datas["assign_data"]["address"] }}</th>
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
                            <th style="color:{{ @$datas["assign_data"]["delivery_color"] }}">{{ @$datas["assign_data"]["delivery_name"] }}</th>
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
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped tm-table-striped-even mt-3"  style="vertical-align: middle;">
                    <thead>
                        <tr class="tm-bg-gray">
                            <th scope="col" class="text-center" height="50px">商品名稱</th>
                            <th scope="col" class="text-center" style="width:15%;">數量</th>
                            <th scope="col" class="text-center" style="width:15%;">售價</th>
                            <th scope="col" class="text-center" style="width:100px;">小計</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($datas["detail_data"]) && !empty($datas["detail_data"]))
                            @foreach($datas["detail_data"] as $data) 
                            <tr>
                                <td class="text-center tm-product-name" height="50px">{{ @$data["name"] }}</td>
                                <td class="text-center">{{ @$data["amount"] }}</td>
                                <td class="text-center">{{ @$data["price"] }}</td>
                                <td class="text-center">{{ @$data["total"] }}元</td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <table class="table table-hover table-striped tm-table-striped-even mt-3"  style="vertical-align: middle;">
                    <thead>
                        <tr class="tm-bg-gray">
                            <th scope="col" height="50px">合計：<span id="total">{{ @$datas["assign_data"]["total"] }}</span>元</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6"></div>
                <div class="col-12 col-sm-6 tm-btn-right">
                    @if(@$datas["assign_data"]["status"] != "cancel")
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#dataModal">修改訂單</button>
                    @endif
                    <button type="button" class="btn btn-primary" onclick="changeForm('/admin/orders')">返回</button>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="form_data" class="tm-signup-form" method="post">
    @csrf
    <input type="hidden" id="input_modal_action_type" name="action_type" value="edit">
    <input type="hidden" id="input_modal_uuid" name="uuid" value="{{ @$datas["assign_data"]["uuid"] }}">
    <div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="semi-bold"><span id="modal_title_name"></span>訂單{{ @$datas["assign_data"]["serial"] }}</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
                    <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
                    <div class="col-12">
                        <div class="row m-t-10">
                            <div class="col-12">
                                <label>配送方式：</label>
                                @if(isset($datas["modal_data"]["delivery"]) && !empty($datas["modal_data"]["delivery"]))    
                                    @foreach($datas["modal_data"]["delivery"] as $key => $val) 
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="delivery" id="input_modal_delivery_{{ @$key }}" value="{{ @$key }}" @if($key == $datas["assign_data"]["delivery"]) checked @endif onclick="changeDataDisplay('checked','delivery','input_modal_address','home',true);">
                                            <label class="form-check-label">{{ @$val }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="col-12">
                                <label>訂單狀態：</label>
                                @if(isset($datas["modal_data"]["status"]) && !empty($datas["modal_data"]["status"]))    
                                    @foreach($datas["modal_data"]["status"] as $key => $val) 
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="input_modal_status_{{ @$key }}" value="{{ @$key }}" @if($key == $datas["assign_data"]["status"]) checked @endif onclick="changeDataDisplay('checked','status','cancel','cancel',true);">
                                            <label class="form-check-label">{{ @$val }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label>收件人姓名</label>
                                <input type="text" id="input_modal_name" name="name" class="form-control require" value="{{ @$datas["assign_data"]["name"] }}" >
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label>收件人手機</label>
                                <input type="text" id="input_modal_phone" name="phone" class="form-control " value="{{ @$datas["assign_data"]["phone"] }}">                  
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label>收件人信箱</label>
                                <input type="text" id="input_modal_email" name="email" class="form-control " value="{{ @$datas["assign_data"]["email"] }}">                  
                            </div>
                            <div id="div_input_modal_address" class="col-12" style="display:none;">
                                <label>收件人地址</label>
                                <input type="text" id="input_modal_address" name="address" class="form-control" value="{{ @$datas["assign_data"]["address"] }}">
                            </div>
                            <div class="col-12">
                                <label>出貨備註：</label>
                                <textarea id="input_modal_delivery_remark" name="delivery_remark" class="form-control" cols="100" rows="3">{!! @$datas["assign_data"]["delivery_remark"] !!}</textarea>
                            </div>
                            <div id="div_cancel" class="col-xl-6 col-lg-6 col-md-12 col-sm-12" style="display:none;">
                                <label class="col-form-label">取消原因</label>
                                <select class="custom-select col-12" id="input_modal_cancel" name="cancel" onchange="changeDataDisplay('select','input_modal_cancel','input_modal_cancel_remark','other',true)">
                                    @if(isset($datas["modal_data"]["cancel"]) && !empty($datas["modal_data"]["cancel"]))
                                        @foreach($datas["modal_data"]["cancel"] as $key => $val)
                                            <option value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div id="div_input_modal_cancel_remark" class="col-xl-6 col-lg-6 col-md-12 col-sm-12" style="display:none;margin-top:20px;">
                                <textarea id="input_modal_cancel_remark" name="cancel_remark" class="form-control" cols="100" rows="3">{!! @$datas["assign_data"]["cancel_remark"] !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-danger btn_submit" onclick="adminSubmit('orders');">送出</button>
                </div>
            </div>
            
        </div>
    </div>
</form>
@endsection

@section('script')
<script>
    $(function () {
        //訂單狀態-已取消顯示取消原因
        changeDataDisplay('checked','status','input_modal_cancel','cancel',true);
        //配送方式-宅配配送顯示收件人地址
        changeDataDisplay('checked','delivery','input_modal_address','home',true);
    });

    $('.dataModalBtn').click(function () {
        var input_modal_keys = [];
        var select_modal_keys = [];
        setModalInput($(this).data('id'),input_modal_keys,select_modal_keys);
    });
</script>
@endsection