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
                <table class="table table-hover table-striped tm-table-striped-even mt-3"  style="vertical-align: middle;">
                    <thead>
                        <tr class="tm-bg-gray">
                            <th scope="col" class="text-center" style="width:15%;" height="50px">序號</th>
                            <th scope="col" class="text-center" style="width:15%;">折價劵</th>
                            <th scope="col" class="text-center" style="width:15%;">金額</th>
                            <th scope="col" class="text-center" style="width:15%;">使用狀態</th>
                            <th scope="col" class="text-center" style="width:20%;">到期時間</th>
                            <th scope="col" class="text-center" style="width:20%;">使用時間</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($datas["list_data"]) && !empty($datas["list_data"]))
                            @foreach($datas["list_data"] as $data)  
                            <tr>
                                <td class="text-center" height="50px">{{ @$data["serial"] }}</td>
                                <td class="text-center">{{ @$data["coupon_name"] }}</td>
                                <td class="text-center">{{ @$data["total"] }}元</td>
                                <td class="text-center" style="color:{{ @$data["status_color"] }}">{{ @$data["status_name"] }}</td>
                                <td class="text-center">{{ @$data["expire_time_format"] }}</td>
                                <td class="text-center">{{ @$data["used_time_format"] }}</td>
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