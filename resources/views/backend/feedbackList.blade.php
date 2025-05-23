@extends('backend.base')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 tm-md-12 tm-sm-12 tm-col">
        <div class="bg-white tm-block">
            <div class="row">
                <div class="form-group col-md-4 col-sm-12">
                    <div class="input-group">
                        <input type="text" id="keywords" name="keywords" class="form-control search_input_data" placeholder="名稱" value="{{ @$datas["assign_data"]["keywords"] }}">
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
                <div class="col-md-2 col-sm-12 text-right" style="margin:10px 0;">
                    <button type="button" class="btn btn-danger check_btn btn_submit" style="display:none" onclick="$('#input_modal_action_type').val('delete');adminSubmit('feedback');">刪除</button>
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
                            <th width="100">名稱</th>
                            <!--<th>年齡</th>
                            <th>居住地</th>-->
                            <th>使用者回饋及感想</th>
                            <th>建立時間</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($datas["list_data"]) && !empty($datas["list_data"]))
                            @foreach($datas["list_data"] as $data) 
                            <tr>
                                <td>
                                    <input id="checkbox_{{ @$data["uuid"] }}" type="checkbox" value="{{ @$data["uuid"] }}" name="check_list[]" onclick="checkId('{{ @$data["uuid"] }}')" class="check_list">
                                </td>
                                <td>
                                    <span class="td-data-span">名稱：</span>
                                    {{ @$data["name"] }}
                                </td>
                                <!--<td>
                                    <span class="td-data-span">年齡：</span>
                                    {{ @$data["age"] }}
                                </td>
                                <td>
                                    <span class="td-data-span">居住地：</span>
                                    {{ @$data["address_county"] }}{{ @$data["address_district"] }}
                                </td>-->
                                <td>
                                    <span class="td-data-span">使用者回饋及感想：<br></span>
                                    {!! @$data["content"] !!}
                                </td>
                                <td>
                                    <span class="td-data-span">建立時間：</span>
                                    {{ @$data["created_at_format"] }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-action">
                                        <ul>
                                            <li>
                                                <i class="fas fa-trash-alt tm-trash-icon btn_submit" onclick="$('#input_modal_action_type').val('delete');$('#check_list').val('{{ @$data["uuid"] }}');adminSubmit('feedback');"></i>
                                            </li>
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

<form id="form_data" class="tm-signup-form" method="post">
    @csrf
    <input type="hidden" id="input_modal_action_type" name="action_type" value="">
    <input type="hidden" id="input_modal_uuid" name="uuid" value="">
    <input type="hidden" id="check_list" name="check_list" value="">
    <div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="semi-bold"><span id="modal_title_name"></span>使用者回饋</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
                    <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
                    <div class="col-12">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn_submit" onclick="adminSubmit('feedback');">送出</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">取消</button>
                </div>
            </div>
            
        </div>
    </div>
</form>
@endsection