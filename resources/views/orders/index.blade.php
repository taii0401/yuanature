@extends('layouts.frontBase')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="content">
    <div class="row tm-content-row tm-mt-big">
        <div class="col-xl-12 col-lg-12 tm-md-12 tm-sm-12 tm-col">
            <div class="bg-white tm-block h-100">
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
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
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
                                <th scope="col" class="text-center" style="width:15%;" height="50px">訂單編號</th>
                                <th scope="col" class="text-center">訂購日期</th>
                                <th scope="col" class="text-center" style="width:12%;">訂單狀態</th>
                                <th scope="col" class="text-center" style="width:12%;">配送方式</th>
                                <th scope="col" class="text-center" style="width:12%;">付款方式</th>
                                <th scope="col" class="text-center" style="width:12%;">訂購金額</th>
                                <th scope="col" style="width:8%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($datas["list_data"]) && !empty($datas["list_data"]))
                                @foreach($datas["list_data"] as $data)  
                                <tr>
                                    <td class="text-center" height="50px">
                                        <a href="#" onclick="changeForm('/orders/detail?orders_uuid={{ @$data["uuid"] }}');">{{ @$data["serial"] }}</a>
                                    </td>
                                    <td class="text-center">{{ @$data["created_at"] }}</td>
                                    <td class="text-center">{{ @$data["status_name"] }}</td>
                                    <td class="text-center">{{ @$data["delivery_name"] }}</td>
                                    <td class="text-center">{{ @$data["payment_name"] }}</td>
                                    <td class="text-center">{{ @$data["total"] }}元</td>
                                    <td>
                                        <div class="col-12">
                                            @if($data["status"] == 1)
                                                <div class="btn-action">
                                                    <i class="fas fa-trash-alt tm-trash-icon dataModalBtn"data-bs-toggle="modal" data-bs-target="#dataModal" data-id="cancel,{{ @$data["uuid"] }},{{ @$data["serial"] }}">
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
</div>

<form id="form_data" class="tm-signup-form" method="post">
    @csrf
    <input type="hidden" id="input_modal_action_type" name="action_type" value="">
    <input type="hidden" id="input_modal_uuid" name="uuid" value="">
    <input type="hidden" id="input_modal_serial" name="serial" value="">
    <div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="semi-bold"><span id="modal_title_name"></span>訂單<span id="serial_text"></span>？</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
                    <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
                    <div class="col-12">
                        <div class="row m-t-10">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label class="col-form-label">取消原因</label>
                                <select class="custom-select col-12" id="input_modal_cancel" name="cancel" onchange="changeSelect()">
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
                    <button type="button" class="btn btn-primary btn_submit" onclick="orderSubmit($('#input_modal_action_type').val());">送出</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">取消</button>
                </div>
            </div>
            
        </div>
    </div>
</form>
@endsection

@section('script')
<script>
    $('.dataModalBtn').click(function () {
        var input_modal_keys = ['action_type','uuid','serial'];
        var select_modal_keys = [];
        setModalInput($(this).data('id'),input_modal_keys,select_modal_keys);

        $('#serial_text').text($('#input_modal_serial').val());
    });

    //取消原因
    function changeSelect() {
        val = $('#input_modal_cancel').val();
        if(val == 3) { //其他
            //顯示取消原因備註
            $('#div_cancel_remark').css('display','');
            $('#cancel_remark').addClass('require');
        } else {
            $('#div_cancel_remark').css('display','none');
            $('#cancel_remark').removeClass('require');
        }
    }
</script>
@endsection