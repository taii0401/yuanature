@extends('backend.base')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 tm-md-12 tm-sm-12 tm-col">
        <div class="bg-white tm-block">
            <div class="row">
                <div class="form-group col-md-4 col-sm-12">
                    <div class="input-group">
                        <input type="text" id="keywords" name="keywords" class="form-control search_input_data" placeholder="序號、會員姓名" value="{{ @$datas["assign_data"]["keywords"] }}">
                        <span class="input-group-btn">
                            <button class="btn btn-secondary" onclick="getSearchUrl('{{ @$datas["assign_data"]["search_link"] }}');"><i class="fas fa-search"></i></button>
                        </span>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <input type="hidden" id="coupon_id" name="coupon_id" class="form-control search_input_data" value="{{ @$datas["assign_data"]["coupon_id"] }}">
                    <input type="hidden" id="status" name="status" class="form-control search_input_data" value="{{ @$datas["assign_data"]["status"] }}">
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
                <div class="col-md-2 col-sm-12 text-right" style="margin:10px 0;">
                    <button type="button" class="btn btn-primary dataModalBtn" data-bs-toggle="modal" data-bs-target="#dataModal" data-id="add">新增</button>
                    <button type="button" class="btn btn-danger check_btn btn_submit" style="display:none" onclick="$('#input_modal_action_type').val('cancel');adminSubmit('user_coupon');">取消</button>
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
                            <th style="width:1%;">
                                <input id="check_all" type="checkbox" value="all" onclick="checkAll()">
                            </th>
                            <th>會員姓名</th>
                            <th>序號</th>
                            <th>折價劵</th>
                            <th>金額</th>
                            <th>使用狀態</th>
                            <th>到期時間</th>
                            <th>使用時間</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($datas["list_data"]) && !empty($datas["list_data"]))
                            @foreach($datas["list_data"] as $data) 
                            <tr>
                                <td>
                                    @if(@$data["status"] == "nouse")
                                        <input id="checkbox_{{ @$data["uuid"] }}" type="checkbox" value="{{ @$data["uuid"] }}" name="check_list[]" onclick="checkId('{{ @$data["uuid"] }}')" class="check_list">
                                    @endif
                                </td>
                                <td>
                                    <span class="td-data-span">會員姓名：</span>
                                    {{ @$data["user_name"] }}
                                </td>
                                <td>
                                    <span class="td-data-span">序號：</span>
                                    {{ @$data["serial"] }}
                                </td>
                                <td>
                                    <span class="td-data-span">折價劵：</span>
                                    {{ @$data["coupon_name"] }}
                                </td>
                                <td>
                                    <span class="td-data-span">金額：</span>
                                    {{ @$data["total"] }}元
                                </td>
                                <td>
                                    <span class="td-data-span">使用狀態：</span>
                                    <span style="color:{{ @$data["status_color"] }}">{{ @$data["status_name"] }}</span>
                                </td>
                                <td>
                                    <span class="td-data-span">到期時間：</span>
                                    {{ @$data["expire_time"] }}
                                </td>
                                <td>
                                    <span class="td-data-span">使用時間：</span><br>
                                    {{ @$data["used_time"] }}
                                </td>
                                <td class="text-center">
                                    @if(@$data["status"] == "nouse")
                                        <div class="btn-action">
                                            <ul>
                                                <li>
                                                    <i class="fas fa-trash-alt tm-trash-icon btn_submit" onclick="$('#input_modal_action_type').val('cancel');$('#check_list').val('{{ @$data["uuid"] }}');adminSubmit('user_coupon');"></i>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
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

<form id="form_data" class="tm-signup-form" method="post">
    @csrf
    <input type="hidden" id="input_modal_action_type" name="action_type" value="">
    <input type="hidden" id="input_modal_uuid" name="uuid" value="">
    <input type="hidden" id="check_list" name="check_list" value="">
    <div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="semi-bold"><span id="modal_title_name"></span>會員折價劵</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
                    <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
                    <div class="col-12">
                        <div class="row m-t-10">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label class="col-form-label">會員姓名</label>
                                <input type="hidden" id="input_modal_user_id" name="user_id" class="form-control" value="" />
                                <input id="input_modal_user_name" name="user_name" list="user_data" class="form-control" value="" />
                                <datalist id="user_data">
                                    @if(isset($datas["modal_data"]["user_id"]) && !empty($datas["modal_data"]["user_id"]))
                                        @foreach($datas["modal_data"]["user_id"] as $key => $val)
                                            <option id="{{ $key }}" value="{{ $key }} - {{ $val }}">
                                        @endforeach
                                    @endif
                                </datalist>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label class="col-form-label">折價劵</label>
                                <select class="custom-select col-12" id="input_modal_coupon_id" name="coupon_id">
                                    @if(isset($datas["modal_data"]["coupon_id"]) && !empty($datas["modal_data"]["coupon_id"]))
                                        @foreach($datas["modal_data"]["coupon_id"] as $key => $val)
                                            <option value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label>到期時間</label>
                                <div class="input-group date" id="input_datetimepicker" data-target-input="nearest">
                                    <input type="text" id="input_modal_expire_time" name="expire_time" class="form-control datetimepicker" data-target="#input_datetimepicker" value="" />
                                    <div class="input-group-append" data-target="#input_datetimepicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn_submit" onclick="adminSubmit('user_coupon');">送出</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">取消</button>
                </div>
            </div>
            
        </div>
    </div>
</form>
@endsection

@section('script')
<script>
    $(function () {
        $('.datetimepicker').datepicker({
            language: 'zh-TW', //中文化
            format: 'yyyy-mm-dd', //格式
            autoclose: true, //選擇日期後就會自動關閉
            todayHighlight: true //今天會有一個底色
        });
    });

    $('.dataModalBtn').click(function () {
        var input_modal_keys = ['action_type','uuid'];
        var switch_modal_keys = [];
        var radio_modal_keys = [];
        setModalInput($(this).data('id'),input_modal_keys,switch_modal_keys,radio_modal_keys);
    });

    //輸入框選單
    $("#input_modal_user_name").change(function() {
        var inputValue = $(this).val();
        var optionValue;
        var ID;
        
        //重複檢查
        $("option").each(function() {
            optionValue = $(this).val();
            if(optionValue == inputValue) {
                $('#input_modal_user_id').val($(this).attr('id'));
                $('#input_modal_user_name').val(inputValue);
            }
        });
    });
</script>
@endsection