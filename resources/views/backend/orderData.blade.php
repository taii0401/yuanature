@extends('backend.base')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 tm-md-12 tm-sm-12 tm-col">
        <div class="bg-white tm-block h-100">
            <div class="col-12 text-right" style="margin:10px 0;">
                <button type="button" class="btn btn-primary" onclick="changeForm('/admin/orders')">返回</button>
            </div>
            <div class="table-responsive" style="margin-top: 10px;">
                @include('tables.order')
                @include('tables.orderUser')
            </div>
            <div class="table-responsive">
                @include('tables.orderCart')
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
                            <div id="div_input_modal_address" class="row input-group twzipcode" style="display:none;margin-top:10px;">
                                <input type="hidden" data-role="zipcode" id="input_modal_address_zip" name="address_zip" class="form-control" value="{{ @$datas["assign_data"]["address_zip"] }}">
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                    <label>地址</label>
                                    <select class="custom-select " data-role="county" id="input_modal_county" name="address_county"></select>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                    <label><br/></label>
                                    <select class="custom-select" data-role="district" id="input_modal_district" name="address_district"></select>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <label><br/></label>
                                    <input type="text" id="input_modal_address" name="address" class="form-control" placeholder="民族路20巷32號" value="{{ @$datas["assign_data"]["address"] }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <label>出貨備註</label>
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
                    <button type="button" class="btn btn-danger btn_submit" onclick="adminSubmit('orders');">送出</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">取消</button>
                </div>
            </div>
            
        </div>
    </div>
</form>
@endsection

@section('script')
<script>
    //縣市、鄉鎮市區、郵遞區號
    const twzipcode = new TWzipcode();
    twzipcode.set("{{ @$datas["assign_data"]["address_zip"] }}");

    $(function () {
        //訂單狀態-已取消顯示取消原因
        changeDataDisplay('checked','status','input_modal_cancel','cancel',true);
        //配送方式-宅配配送顯示收件人地址
        changeDataDisplay('checked','delivery','input_modal_address','home',true);
    });

    $('.dataModalBtn').click(function () {
        setModalInput($(this).data('id'),[],[],[]);
    });
</script>
@endsection