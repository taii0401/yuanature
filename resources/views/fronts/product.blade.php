<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>廣志足浴露</title>
</head>

<body>
    @for($i = 1;$i <= 14;$i++)
    <img src="../../img/product/product_{{ $i }}.jpg" width="100%">
    @endfor
</body>
</html>