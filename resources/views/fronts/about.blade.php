@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('content')
<div class="content" style="margin-top:0;background-image:url({{ asset('img/about_bg.jpg') }});background-size: auto;">
    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 content-bg-about">
            <h4 class="mb-4">關於｜原生學</h4>
            <p class="mb-4">｜回復原生肌膚般的純淨自然保養美學｜</p>
            <p class="mb-4">肌膚保養品都提倡「減量」哲學，減去過多的添加物、保留對肌膚最友善的原料，但我們希望帶來的是「剛好」的哲學，就像想要髮質滑順，你必須用對東西；想要肌膚滋潤，你必須擦對物品。用對了，才會有效，就像我們感冒並不會只吃胃藥吧。</p>
            <p class="mb-4">剛出生的寶寶肌膚最為稚嫩，經過環境、飲食、保養以及年齡的催化，肌膚的狀態不斷變化。我們相信「潔淨」是靜止肌膚狀態劣化的首要關鍵，水分及油脂的平衡打造更健康的肌膚，解決各種膚質問題，回復原生肌膚般的潔淨感受，我們希望帶給你們更多關於潔淨的體驗。</p>
            <p style="margin-top: 30px;"></p>
            <h4 class="mb-4">關於｜廣志足白浴露</h4>
            <p class="mb-4">第一支產品的誕生真的非常艱難，你會看到市面上的足部清潔產品真的很稀少，這是因為台灣氣候炎熱且潮濕，要做到去除腳臭這個課題真的是個大挑戰。直到『廣志足白浴露』的出現，使用完隔天還能感覺到清爽的雙足、穿鞋一整天明顯的降低黏膩感，希望你們能走出自信，跟著我們從每天展開的第一步開始。</p>
        </div>
        <div class="clearfix"> </div>
    </div>
</div>
@endsection