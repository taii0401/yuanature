<form id="form_data_pay" class="tm-signup-form" method="post">
    @csrf
    <input type="hidden" id="input_modal_pay_source" name="source" value="">
    <input type="hidden" id="input_modal_pay_action_type" name="action_type" value="pay">
    <input type="hidden" id="input_modal_pay_uuid" name="uuid" value="">
    <input type="hidden" id="input_modal_pay_serial" name="serial" value="">
    <div class="modal fade" id="dataModalOrderPay" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="semi-bold">付款及配送方式 - <span id="pay_serial_text"></span></h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
                    <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
                    <div class="col-12">
                        <div class="row m-t-10">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label><span class="star">* </span>付款方式</label>
                                <div class="col-12">
                                    @if(isset($datas["modal_data"]["payment"]) && !empty($datas["modal_data"]["payment"]))
                                        @foreach($datas["modal_data"]["payment"] as $key => $val)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="payment" id="input_modal_payment_{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label">{{ $val }}</label>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>                  
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label><span class="star">* </span>配送方式</label>
                                <div class="col-12">
                                    @if(isset($datas["modal_data"]["delivery"]) && !empty($datas["modal_data"]["delivery"]))
                                        @foreach($datas["modal_data"]["delivery"] as $key => $val)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="delivery" id="input_modal_delivery_{{ $key }}" value="{{ $key }}" onclick="changeDataDisplay('checked','delivery','input_modal_address','home',true);">
                                            <label class="form-check-label">{{ $val }}</label>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>                  
                            </div>
                            <div id="div_input_modal_address" class="row input-group twzipcode" style="display:none;margin-top:10px;">
                                <input type="hidden" data-role="zipcode" id="input_modal_address_zip" name="address_zip" class="form-control" value="{{ @$datas["assign_data"]["address_zip"] }}">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <label><span class="star">* </span>地址</label>
                                    <select class="custom-select " data-role="county" id="input_modal_county" name="address_county"></select>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <label><br/></label>
                                    <select class="custom-select" data-role="district" id="input_modal_district" name="address_district"></select>
                                </div>
                                <div class="col-12">
                                    <label><br/></label>
                                    <input type="text" id="input_modal_address" name="address" class="form-control" placeholder="民族路20巷32號" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn_submit" onclick="orderPaySubmit();">送出</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function orderPaySubmit() {
        var source = $('#input_modal_pay_source').val();
        var action_type = $('#input_modal_pay_action_type').val();

        if(source == 'admin') {
            adminSubmit('orders');
        } else {
            orderSubmit(action_type);
        }
    }
</script>