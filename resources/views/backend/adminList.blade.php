@extends('backend.base')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="row tm-content-row tm-mt-big">
    <div class="col-xl-12 col-lg-12 tm-md-12 tm-sm-12 tm-col">
        <div class="bg-white tm-block h-100">
            <div class="row">
                <div class="form-group col-md-4 col-sm-12">
                    <div class="input-group">
                        <input type="text" id="keywords" name="keywords" class="form-control search_input_data" placeholder="帳號、名稱" value="{{ @$datas["assign_data"]["keywords"] }}">
                        <span class="input-group-btn">
                            <button class="btn btn-secondary" onclick="getSearchUrl('{{ @$datas["assign_data"]["search_link"] }}');"><i class="fas fa-search"></i></button>
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <input type="hidden" id="status" name="status" class="form-control search_input_data" value="{{ @$datas["assign_data"]["status"] }}">
                    
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
                <div class="col-md-4 col-sm-12 text-right">
                    <button type="button" class="btn btn-primary dataModalBtn" data-bs-toggle="modal" data-bs-target="#dataModal" data-id="add">新增</button>
                    <button type="button" class="btn btn-danger check_btn" style="display:none" onclick="adminSubmit('admin');">刪除</button>
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
                            <th scope="col" class="text-center" style="width:1%;">
                                <div class="custom-control custom-checkbox">
                                    <input id="check_all" type="checkbox" value="all" onclick="checkAll()">
                                    <label for="check_all"></label>
                                </div>
                            </th>
                            <th scope="col" style="width:30%;">帳號</th>
                            <th scope="col">名稱</th>
                            <th scope="col" style="width:15%;">是否啟用</th>
                            <th scope="col" style="width:15%;">群組</th>
                            <th scope="col" style="width:15%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($datas["list_data"]) && !empty($datas["list_data"]))
                            @foreach($datas["list_data"] as $data) 
                            <tr>
                                <td scope="row">
                                    <div class="custom-control custom-checkbox">
                                        <input id="checkbox_{{ @$data["uuid"] }}" type="checkbox" value="{{ @$data["uuid"] }}" name="check_list[]" onclick="checkId('{{ @$data["uuid"] }}')" class="check_list">
                                        <label for="checkbox_{{ @$data["uuid"] }}"></label>
                                    </div>
                                </td>
                                <td>{{ @$data["account"] }}</td>
                                <td>{{ @$data["name"] }}</td>
                                <td>{{ @$data["status_name"] }}</td>
                                <td>{{ @$data["admin_group_name"] }}</td>
                                <td>
                                    <div class="col-12">
                                        <div class="btn-action">
                                            <i class="fas fa-edit tm-edit-icon dataModalBtn"data-bs-toggle="modal" data-bs-target="#dataModal" data-id="edit,{{ @$data["uuid"] }},{{ @$data["account"] }},{{ @$data["name"] }},{{ @$data["status"] }},{{ @$data["admin_group_id"] }}">
                                            </i>
                                        </div>
                                        <div class="btn-action">
                                            <i class="fas fa-trash-alt tm-trash-icon" onclick="$('#input_modal_action_type').val('delete');$('#check_list').val('{{ @$data["uuid"] }}');adminSubmit('admin');"></i>
                                        </div>
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

<form id="form_data" class="tm-signup-form" method="post">
    @csrf
    <input type="hidden" id="input_modal_action_type" name="action_type" value="">
    <input type="hidden" id="input_modal_uuid" name="uuid" value="">
    <input type="hidden" id="check_list" name="check_list" value="">
    <div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="semi-bold"><span id="modal_title_name"></span>管理員</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
                    <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
                    <div class="col-12">
                    <div class="row m-t-10">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label>帳號</label>
                                <input type="text" id="input_modal_account" name="account" class="form-control require" value="" >
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label class="col-12">是否啟用</label>
                                <label class="form-switch">
                                    <input type="checkbox" id="input_modal_status" name="status" class="form-control" onclick="changeSwitch('input_modal_status');">
                                    <i></i> <span id="input_switch_text_input_modal_status"></span>
                                </label>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label>密碼<span class="password_text" style="color:red;font-size:x-small"></span></label>
                                <input type="password" id="input_modal_password" name="password" class="form-control " value="" placeholder="輸入6~30個英文字或數字">                  
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label>確認密碼<span class="password_text" style="color:red;font-size:x-small"></span></label>
                                <input type="password" id="input_modal_confirm_password" name="confirm_password" class="form-control " value="" placeholder="請輸入相同的登入密碼">
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label>名稱</label>
                                <input type="text" id="input_modal_name" name="name" class="form-control require" value="">
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label class="col-form-label">群組</label>
                                <select class="custom-select col-12" id="input_modal_admin_group_id" name="admin_group_id">
                                    @if(isset($datas["modal_data"]["admin_group"]) && !empty($datas["modal_data"]["admin_group"]))
                                        @foreach($datas["modal_data"]["admin_group"] as $key => $val)
                                            <option value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="adminSubmit('admin');">送出</button>
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
        //是否啟用
        changeSwitch('input_modal_status');
    });

    $('.dataModalBtn').click(function () {
        var input_modal_keys = ['action_type','uuid','account','name','status','admin_group_id'];
        var select_modal_keys = ['status'];
        setModalInput($(this).data('id'),input_modal_keys,select_modal_keys);

        var action_type = $('#input_modal_action_type').val();
        if(action_type == 'add') {
            $('.password_text').text('');
        } else if(action_type == 'edit') {
            $('.password_text').text('(若不修改則不需要輸入)');
        }
    });
</script>
@endsection