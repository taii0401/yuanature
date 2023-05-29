@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('banner_menu_txt') 使用者回饋 > {{ @$title_txt }} @endsection
@section('content')
<div class="row ">
    <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 mx-auto">
        <div class="row">
            <div class="col-12 mx-auto tm-login">
                <div class="bg-white tm-block">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h2 class="tm-block-title mt-3">{{ @$title_txt }}</h2>
                        </div>
                    </div>
                
                    <div class="row">
                        <div id="msg_error" class="col-12 alert alert-danger" role="alert" style="display:none;"></div>
                        <div id="msg_success" class="col-12 alert alert-success" role="alert" style="display:none;"></div>
                        <div class="col-12">
                            <p>
                                歡迎留下您使用過後的想法及建議事項，讓我們能有更多改進的空間，如果您同意本網站使用您的留言及上傳的照片，請為我們勾選同意。有您的支持是我們莫大的榮幸，原生學再次感謝您！
                            </p>
                            <form id="form_data" class="tm-signup-form" method="post">
                                @csrf
                                <input type="hidden" id="action_type" name="action_type" value="add">
                                <div class="row m-t-10" >
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label>名稱</label>
                                        <input type="text" id="name" name="name" class="form-control require" value="">
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label>年齡</label>
                                        <input type="number" id="age" name="age" value="" class="form-control" width="100%;">
                                    </div>
                                </div>
                                <div class="row input-group twzipcode" style="margin-top:10px;">
                                    <input type="hidden" data-role="zipcode" id="address_zip" name="address_zip" class="form-control" value="">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label>居住地</label>
                                        <select class="custom-select require" data-role="county" id="county" name="address_county"></select>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label><br/></label>
                                        <select class="custom-select require" data-role="district" id="district" name="address_district"></select>
                                    </div>
                                </div>
                                <div class="row m-t-10" style="margin-top:20px;">
                                    <div class="col-12">
                                        <label>使用者回饋及感想</label>
                                        <textarea id="content" name="content" rows="5" class="form-control require"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label>照片上傳<span style="color:red;font-size:x-small"> (限制大小：300KB)</span></label>
                                        <input type="hidden" id="folder_name" name="folder_name" value="feedback" class="form-control">
                                        <input type="file" id="file" name="file" multiple="" accept="image/x-png,image/jpg,image/jpeg"/>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="agree" id="agree" value="1" onclick="$('#btn_feedback').css('display', '');">
                                            <label class="form-check-label"><span class="star">本人確認已詳閱下述「個人資料蒐集、處理、利用告知事項」 (請勾選)。</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row input-group mt-3">
                                    <div class="col-12 tm-btn-center">
                                        <button id="btn_feedback" type="button" class="btn btn-primary btn_submit" style="display:none" onclick="feedbackSubmit('add');">送出</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mx-auto">
                <div class="bg-white tm-block" style="padding:30px;">
                    <h5 class="mb-4">個人資料蒐集、處理、利用告知事項</h5>
                    <p>白羊作坊暨原生學為遵守個人資料保護法規定，在您提供個人資料予本公司前，依法告知下列事項：</p>
                    <ul>
                        <li>白羊作坊(以下簡稱本公司)為提供本公司品牌原生學資訊、行銷、廣告及活動資訊等目的而獲取您下列個人資料類別：姓名、居住地、年齡、照片等，或其他得以直接或間接識別您個人之資料。 </li>
                        <li>本公司將於蒐集目的之存續期間合理利用您的個人資料。</li>
                        <li>本公司僅於中華民國領域內利用您的個人資料。</li>
                        <li>本公司將於原蒐集之特定目的、本次以外之品牌推廣、行銷等範圍內，合理利用您的個人資料。</li>
                        <li>您可依個人資料保護法第 3 條規定，就您的個人資料向本公司行使之下列權利： (一) 查詢或請求閱覽。 (二) 請求製給複製本。 (三) 請求補充或更正。 (四) 請求停止蒐集、處理及利用。 (五) 請求刪除。</li>
                        <li>若您未提供正確之個人資料，本公司將無法為您提供特定目的之相關業務。</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- 上傳檔案 -->
<script src="{{ mix('packages/upload/jquery.dm-uploader.min.js') }}"></script>
<script src="{{ mix('packages/upload/jquery.dm-uploader-ui.js') }}"></script>
<script>
    //縣市、鄉鎮市區、郵遞區號
    const twzipcode = new TWzipcode();
    twzipcode.set("{{ @$address_zip }}");
</script>
@endsection
