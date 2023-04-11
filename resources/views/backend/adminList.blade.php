@extends('backend.base')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<form id="form_data" class="tm-signup-form" method="post">
    @csrf
    <input type="hidden" id="action_type" name="action_type" value="{{ @$assign_data["action_type"] }}">
    <input type="hidden" id="user_id" name="user_id" value="{{ @$assign_data["user_id"] }}">
    <input type="hidden" id="check_list" name="check_list" value="">
</form>
<div class="row tm-content-row tm-mt-big">
    <div class="col-xl-12 col-lg-12 tm-md-12 tm-sm-12 tm-col">
        <div class="bg-white tm-block h-100">
            <div class="row">
                <div class="form-group col-md-4 col-sm-12">
                    <div class="input-group">
                        <input type="text" id="keywords" name="keywords" class="form-control search_input_data" placeholder="帳號、名稱" value="{{ @$datas["assign_data"]["keywords"] }}">
                        <span class="input-group-btn">
                            <button class="btn btn-secondary" onclick="getSearchUrl('/admin/list');"><i class="fas fa-search"></i></button>
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <input type="hidden" id="status" name="status" class="form-control search_input_data" value="{{ @$datas["assign_data"]["status"] }}">
                    <input type="hidden" id="orderby" name="orderby" class="form-control search_input_data" value="{{ @$datas["assign_data"]["orderby"] }}">
                    <div class="dropdown btn-group">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            是否啟用
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @if(isset($datas["option"]["types"]))    
                                @foreach($option_datas["types"] as $key => $val) 
                                <a class="dropdown-item @if($assign_data["types"] == $key) active @endif" href="#" onclick="$('#types').val('{{ @$key }}');getSearchUrl('{{ @$assign_data["search_link"] }}');">{{ @$val }}</a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="dropdown btn-group">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            排序
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @if(isset($option_datas["orderby"]))
                                @foreach($option_datas["orderby"] as $key => $val) 
                                <a class="dropdown-item @if($assign_data["orderby"] == $key) active @endif" href="#" onclick="$('#orderby').val('{{ @$key }}');getSearchUrl('{{ @$assign_data["search_link"] }}');">{{ @$val }}</a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 text-right">
                    <button type="button" class="btn btn-primary" onclick="changeForm('/products/create');">新增</button>
                    <button type="button" class="btn btn-danger check_btn" style="display:none" onclick="productSubmit('delete_list');">刪除</button>
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
                            <th scope="col" class="text-center" style="width:30%;">帳號</th>
                            <th scope="col" class="text-center">名稱</th>
                            <th scope="col" class="text-center" style="width:15%;">是否啟用</th>
                            <th scope="col" class="text-center" style="width:15%;">群組</th>
                            <th scope="col" class="text-center" style="width:20%;">動作</th>
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
                                <td class="text-center">{{ @$data["account"] }}</td>
                                <td class="text-center">{{ @$data["name"] }}</td>
                                <td class="text-center">{{ @$data["status_name"] }}</td>
                                <td class="text-center">{{ @$data["admin_group_name"] }}</td>
                                <td class="text-center">
                                    <div style="margin:0 auto;">
                                        <div class="btn-action">
                                            <i class="fas fa-edit tm-edit-icon" onclick="changeForm('/products/edit?uuid={{ @$data["uuid"] }}');"></i>
                                        </div>
                                        <div class="btn-action">
                                            <i class="fas fa-trash-alt tm-trash-icon" onclick="$('#check_list').val('{{ @$data["uuid"] }}');productSubmit('delete_list');"></i>
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
@endsection