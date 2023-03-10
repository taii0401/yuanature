@extends('layouts.front_base')

@section('content')
@for($i = 1;$i <= 14;$i++)
<img src="../../img/sales/sales_{{ $i }}.jpg" width="100%">
@endfor

<div class="row tm-content-row tm-mt-big" style="position: fixed;right: 0px;top: 10%;width: 50%;margin-top: -2.5em; display:none;">
    <div class="col-xl-12 col-lg-12 tm-md-12 tm-sm-12 tm-col">
        <div class="tm-block h-100">
            <div class="row">
                <div class="form-group col-md-4 col-sm-12">
                    <div class="input-group">
                        <input type="text" id="keywords" name="keywords" class="form-control search_input_data" placeholder="編號、名稱" value="{{ @$assign_data["keywords"] }}">
                        <span class="input-group-btn">
                            <button class="btn btn-secondary" onclick="getSearchUrl('{{ @$assign_data["search_link"] }}');"><i class="fas fa-search"></i></button>
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <input type="hidden" id="orderby" name="orderby" class="form-control search_input_data" value="{{ @$assign_data["orderby"] }}">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection