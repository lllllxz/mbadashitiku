<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $datas['title'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="{{ asset('vendor/laravel-admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <h3 id="export" style="text-align: center">{{ $datas['title'] }}</h3>
        <a class="btn btn-danger" style="float:right" href="logic/download?k={{ $datas['title'] }}">导出Word</a>
        @foreach($datas['data'] as $data)
            <div class="item" style="padding: 20px;">
                <h4>【 单项选择 】{{ $loop->iteration }}</h4>
                <p>{{ $data['content'] }}</p>
                <ul class="list-group">
                    @foreach($data['optionList'] as $option)
                        <li class="list-group-item">{{ $option->sign }}. {{ $option->content }}</li>
                    @endforeach
                </ul>
                <p><b>解析：</b>{{ $data['analysis'] }}</p>
            </div>
        @endforeach
    </div>
</div>
<script type="text/javascript" >
    $(function () {
        var val = $("#export").text();
        window.open().location='math/download?k='+val;
    })
</script>
</body>
</html>