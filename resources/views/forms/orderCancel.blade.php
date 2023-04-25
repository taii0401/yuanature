<form id="form_data_cancel" class="tm-signup-form" method="post">
    @csrf
    <input type="hidden" id="input_modal_source" name="source" value="">
    <input type="hidden" id="input_modal_action_type" name="action_type" value="cancel">
    <input type="hidden" id="input_modal_uuid" name="uuid" value="">
    <input type="hidden" id="input_modal_serial" name="serial" value="">
    <div class="modal fade" id="dataModalOrderCancel" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="semi-bold">取消訂單<span id="serial_text"></span>？</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
                    <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
                    <div class="col-12">
                        <div class="row m-t-10">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label class="col-form-label">取消原因</label>
                                <select class="custom-select col-12" id="input_modal_cancel" name="cancel" onchange="changeDataDisplay('select','input_modal_cancel','cancel_remark','other',true)">
                                    @if(isset($datas["modal_data"]["cancel"]) && !empty($datas["modal_data"]["cancel"]))
                                        @foreach($datas["modal_data"]["cancel"] as $key => $val)
                                            <option value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div id="div_cancel_remark" class="col-xl-6 col-lg-6 col-md-12 col-sm-12" style="display:none;margin-top:20px;">
                                <textarea id="cancel_remark" name="cancel_remark" class="form-control" cols="100" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-danger btn_submit" onclick="orderCancelSubmit();">送出</button>
                </div>
            </div>
            
        </div>
    </div>
</form>

<script>
    function orderCancelSubmit() {
        var source = $('#input_modal_source').val();
        var action_type = $('#input_modal_action_type').val();

        if(source == 'admin') {
            adminSubmit('orders');
        } else {
            orderSubmit(action_type);
        }
    }
</script>