@extends('layouts.frontBase')
@section('title') {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('banner_menu_txt') 會員中心 > {{ @$datas["assign_data"]["title_txt"] }} @endsection
@section('content')
<div class="row tm-mt-big">
    @include('layouts.frontUser')
    <div class="col-xl-10 col-lg-10 col-md-10 col-sm-12 mx-auto tm-login">
        <div class="bg-white tm-block">
            <div class="row">
                <div class="form-group col-md-4 col-sm-12">
                    
                </div>
                <div class="col-md-6 col-sm-12">
                    
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered table-rwd">
                    <thead>    
                        <tr class="tr-only-hide text-center">
                            <th>序號</th>
                            <th>折價劵</th>
                            <th>金額</th>
                            <th>使用狀態</th>
                            <th>到期時間</th>
                            <th>使用時間</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($datas["list_data"]) && !empty($datas["list_data"]))
                            @foreach($datas["list_data"] as $data) 
                            <tr>
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
                                    <span class="td-data-span">使用時間：</span>
                                    {{ @$data["used_time"] }}
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection