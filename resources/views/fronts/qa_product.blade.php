@extends('layouts.frontBase')
@section('title') {{ @$title_txt }} @endsection
@section('banner_menu_txt') 常見問題 > {{ @$title_txt }} @endsection
@section('content')
<div class="row">
    <div class="content-info col-xl-7 col-lg-7 col-md-10 col-sm-10">
        <h4 class="mb-4">｜產品問題｜</h4>
        <div class="qa">
            <p class="mb-4">
                <span class="qa-q">Q：產品標示建議浸泡10 ~ 12分鐘，我可以泡更久嗎？</span><br/>
                <span class="qa-a">A：每個人的肌膚敏感度不同，建議使用10 ~ 12分鐘即有很好的效果。</span>
            </p>
        </div>
        <div class="qa">
            <p class="mb-4">
                <span class="qa-q">Q：廣志足白浴露是哪裡製造的？</span><br/>
                <span class="qa-a">A：我們是台灣原生品牌，從研發到製造都是台灣製造的優質產品。</span>
            </p>
        </div>
        <div class="qa">
            <p class="mb-4">
                <span class="qa-q">Q：敏感性肌膚及孕婦可否使用？</span><br/>
                <span class="qa-a">A：當然可以。且經SGS檢驗絕無重金屬殘留，不管是敏感肌膚或是孕婦都可以安心使用。</span>
            </p>
        </div>
        <div class="qa">
            <p class="mb-4">
                <span class="qa-q">Q：足部有傷口可以使用嗎？</span><br/>
                <span class="qa-a">A：有開放性傷口的時候不建議使用，請於傷口癒合後再使用本產品。</span>
            </p>
        </div>
        <div class="qa">
            <p class="mb-4">
                <span class="qa-q">Q：產品保存方式有何限制？</span><br/>
                <span class="qa-a">A：一般保養品保存方式都不建議放置於太陽直射的地方，因為太陽直射溫度升高可能會讓內容物變質，降低原有效用，所以請放置於陰涼處。</span>
                <span style="color:red">(注意！是陰涼處，不是冰箱… )</span>
            </p>
        </div>
    </div>
</div>
@endsection