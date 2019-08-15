<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
    <title>百度Ocr识别</title>
</head>
<body>
    <div style="margin: 30px;">
        <form method="post" action="/ocr/export" enctype="multipart/form-data">
            <input type="file" name="image" accept=".jpg" required>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" value="直接导出" >
        </form>
    </div>
    <div style="margin: 30px;">
        <form id="upload" enctype="multipart/form-data">
            <input type="file" id="image" accept=".jpg" required>
            <input type="hidden" name="_token" id="ajax_token" value="{{ csrf_token() }}">
            <input type="button" id="submit" value="提交">
        </form>
    </div>

    <div style="margin: 30px;">
        <textarea name="word" id="word" cols="150" rows="40" readonly></textarea>
    </div>
</body>
<script type="text/javascript" >
    $("#submit").on('click', function () {
        var formData = new FormData();
        var image = $("#image")[0].files[0];

        if (image == undefined){
            alert('请先选择文件');
            return false;
        }
        formData.append("image", image)
        formData.append("_token", $("#ajax_token").val())
        $.ajax({
            url: "/ocr/change",
            type: "post",
            data: formData,
            processData : false,
            contentType : false,
            success: function (data) {
                var text = data.data;
                console.log(text);
                $("#word").val(text);
            }
        })
    })
</script>
</html>
