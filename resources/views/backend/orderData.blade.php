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
                            <th>{{ @$datas["assign_data"]["serial"] }}</th>
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
                            <th class="text-center tm-bg-gray" height="50px">收件人電話：</th>
                            <th>{{ @$datas["assign_data"]["phone"] }}</th>
                        </tr>
                        @if(@$datas["assign_data"]["delivery"] == 2 && @$datas["assign_data"]["address"] != "")
                            <tr>
                                <th class="text-center tm-bg-gray" height="50px">收件人地址：</th>
                                <th>{{ @$datas["assign_data"]["address"] }}</th>
                            </tr>
                        @endif
                        <tr>
                            <th class="text-center tm-bg-gray" height="50px">訂單狀態：</th>
                            <th>{{ @$datas["assign_data"]["status_name"] }}</th>
                        </tr>
                        @if(@$datas["assign_data"]["status"] == 4)
                            <tr>
                                <th class="text-center tm-bg-gray" height="50px">取消原因：</th>
                                <th>{{ @$datas["assign_data"]["cancel_name"] }}</th>
                            </tr>
                        @endif
                        <tr>
                            <th class="text-center tm-bg-gray" height="50px">配送方式：</th>
                            <th>{{ @$datas["assign_data"]["delivery_name"] }}</th>
                        </tr>
                        <tr>
                            <th class="text-center tm-bg-gray" height="50px">付款方式：</th>
                            <th>{{ @$datas["assign_data"]["payment_name"] }}</th>
                        </tr>
                        <tr>
                            <th class="text-center tm-bg-gray" height="50px">訂購金額：</th>
                            <th>{{ @$datas["assign_data"]["total"] }}元</th>
                        </tr>
                        <tr>
                            <th class="text-center tm-bg-gray" height="50px">訂單備註：</th>
                            <th>{!! @$datas["assign_data"]["order_remark_format"] !!}</th>
                        </tr>
                        @if(@$datas["assign_data"]["status"] == 4 && @$datas["assign_data"]["cancel"] == 3 && @$datas["assign_data"]["cancel_remark"] != "")
                            <tr>
                                <th class="text-center tm-bg-gray" height="50px">取消備註：</th>
                                <th>{!! @$datas["assign_data"]["cancel_remark_format"] !!}</th>
                            </tr>
                        @endif
                    </thead>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped tm-table-striped-even mt-3"  style="vertical-align: middle;">
                    <thead>
                        <tr class="tm-bg-gray">
                            <th scope="col" class="text-center" height="50px">商品名稱</th>
                            <th scope="col" class="text-center" style="width:10%;">數量</th>
                            <th scope="col" class="text-center" style="width:10%;">售價</th>
                            <th scope="col" class="text-center" style="width:15%;">小計</th>
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#dataModal" data-id="edit,{{ @$datas["assign_data"]["uuid"] }},{{ @$datas["assign_data"]["status"] }},{{ @$datas["assign_data"]["delivery"] }},{{ @$datas["assign_data"]["payment"] }}">修改訂單</button>
                    <button type="button" class="btn btn-primary" onclick="changeForm('/admin/orders')">返回</button>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="form_data" class="tm-signup-form" method="post">
    @csrf
    <input type="hidden" id="input_modal_action_type" name="action_type" value="">
    <input type="hidden" id="input_modal_uuid" name="uuid" value="">
    <input type="hidden" id="check_list" name="check_list" value="">
    <div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="semi-bold"><span id="modal_title_name"></span>會員</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
                    <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
                    <div class="col-12">
                        <div class="row m-t-10">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label>姓名</label>
                                <input type="text" id="input_modal_name" name="name" class="form-control require" value="" >
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label>EMAIL</label>
                                <input type="text" id="input_modal_email" name="email" class="form-control " value="">                  
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label>手機</label>
                                <input type="text" id="input_modal_phone" name="phone" class="form-control " value="">
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label class="col-12">是否驗證</label>
                                <label class="form-switch">
                                    <input type="checkbox" id="input_modal_is_verified" name="is_verified" class="form-control" onclick="changeSwitch('input_modal_is_verified');">
                                    <i></i> <span id="input_switch_text_input_modal_is_verified"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn_submit" onclick="adminSubmit('user');">送出</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">取消</button>
                </div>
            </div>
            
        </div>
    </div>
</form>
@endsection

@section('script')
<script>
    $(function () {
        //是否驗證
        changeSwitch('input_modal_is_verified');
    });

    $('.dataModalBtn').click(function () {
        var input_modal_keys = ['action_type','uuid','name','email','phone','is_verified'];
        var select_modal_keys = ['is_verified'];
        setModalInput($(this).data('id'),input_modal_keys,select_modal_keys);
    });
</script>
@endsection