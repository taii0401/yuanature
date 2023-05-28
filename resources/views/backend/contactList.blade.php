@extends('backend.base')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 tm-md-12 tm-sm-12 tm-col">
        <div class="bg-white tm-block">
            <div class="row">
                <div class="form-group col-md-4 col-sm-12">
                    <div class="input-group">
                        <input type="text" id="keywords" name="keywords" class="form-control search_input_data" placeholder="姓名、電子郵件、連絡電話" value="{{ @$datas["assign_data"]["keywords"] }}">
                        <span class="input-group-btn">
                            <button class="btn btn-secondary" onclick="getSearchUrl('{{ @$datas["assign_data"]["search_link"] }}');"><i class="fas fa-search"></i></button>
                        </span>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <input type="hidden" id="type" name="type" class="form-control search_input_data" value="{{ @$datas["assign_data"]["type"] }}">
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
                <div class="col-md-2 col-sm-12 text-right">
                    <button type="button" class="btn btn-danger check_btn btn_submit" style="display:none" onclick="$('#input_modal_action_type').val('delete');adminSubmit('contact');">刪除</button>
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
                            <th class="text-center" scope="col" style="width:10%;">姓名</th>
                            <th class="text-center" scope="col" style="width:15%;">電子郵件</th>
                            <th class="text-center" scope="col" style="width:8%;">聯絡電話</th>
                            <th class="text-center" scope="col" style="width:10%;">問題類型</th>
                            <th class="text-center" scope="col" style="width:8%;">處理狀態</th>
                            <th class="text-center" scope="col">訊息內容</th>
                            <th class="text-center" scope="col" style="width:10%;">建立時間</th>
                            <th scope="col" style="width:12%;"></th>
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
                                <td class="text-center">{{ @$data["name"] }}</td>
                                <td class="text-center">{{ @$data["email"] }}</td>
                                <td class="text-center">{{ @$data["phone"] }}</td>
                                <td class="text-center" style="color:{{ @$data["type_color"] }}">{{ @$data["type_name"] }}</td>
                                <td class="text-center" style="color:{{ @$data["status_color"] }}">{{ @$data["status_name"] }}</td>
                                <td class="text-left">{!! @$data["content"] !!}</td>
                                <td class="text-center">{{ @$data["created_at_format"] }}</td>
                                <td>
                                    <div class="col-12">
                                        <div class="btn-action">
                                            <i class="fas fa-edit tm-edit-icon dataModalBtn"data-bs-toggle="modal" data-bs-target="#dataModal" data-id="edit,{{ @$data["uuid"] }},{{ @$data["status"] }},{{ @$data["reply"] }}">
                                            </i>
                                        </div>
                                        <div class="btn-action">
                                            <i class="fas fa-trash-alt tm-trash-icon btn_submit" onclick="$('#input_modal_action_type').val('delete');$('#check_list').val('{{ @$data["uuid"] }}');adminSubmit('contact');"></i>
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
                    <h6 class="semi-bold"><span id="modal_title_name"></span>聯絡我們</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
                    <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
                    <div class="col-12">
                        <div class="row m-t-10">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label class="col-form-label">處理狀態</label>
                                <select class="custom-select col-12" id="input_modal_status" name="status">
                                    @if(isset($datas["modal_data"]["status"]) && !empty($datas["modal_data"]["status"]))
                                        @foreach($datas["modal_data"]["status"] as $key => $val)
                                            <option value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="col-form-label">回覆內容</label>
                                <textarea id="input_modal_reply" name="reply" class="form-control" cols="100" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn_submit" onclick="adminSubmit('contact');">送出</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">取消</button>
                </div>
            </div>
            
        </div>
    </div>
</form>
@endsection

@section('script')
<script>
    $('.dataModalBtn').click(function () {
        var input_modal_keys = ['action_type','uuid','status','reply'];
        var switch_modal_keys = radio_modal_keys = [];
        setModalInput($(this).data('id'),input_modal_keys,switch_modal_keys,radio_modal_keys);
    });
</script>
@endsection