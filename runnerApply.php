<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>叮叮猫</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <link rel="stylesheet" href="js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css"/>
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script src="js/runnerApply.js"></script>
    <link rel="stylesheet" href="style/register.css"/>
</head>
<body>
<div data-role="page" id="register">
    <div class="header" data-role="header" data-position="fixed" class = "header">
        <h1>跑腿哥报名</h1>
    </div>
    <form action="./api/index.php?s=/Home/Account/runnerApply" method="post" enctype="multipart/form-data" data-ajax="false" class="form">
        <div role="main" class="ui-content">
            <div class="ui-field-contain">
                <label>姓名：</label>
                <input class="usrName" name="userName" type="text"/>
            </div>
            <div class="ui-field-contain">
                <label>身份证号：</label>
                <input type="text" name="idCardNo" class="idCard" />
            </div>
            <div class="ui-field-contain">
                <label>身份证正面：</label>
                <button type="button" id="button1" onclick="img_upload1.click()">选择图片</button>
            </div>
            <input id="img_upload1" name="idCardPic[]" type="file" multiple="true" style="display: none" onchange="picJudge1()">
            <div class="ui-field-contain">
                <label>身份证背面：</label>
                <button type="button" id="button2" onclick="img_upload2.click()">选择图片</button>
            </div>
            <input id="img_upload2" name="idCardPic[]" type="file" multiple="true" style="display: none" onchange="picJudge2()">
            <div class="ui-field-contain">
                <label>身份证手持：</label>
                <button type="button" id="button3" onclick="img_upload3.click()">选择图片</button>
            </div>
            <input id="img_upload3" name="idCardPic[]" type="file" multiple="true" style="display: none" onchange="picJudge3()">
            <div class="ui-field-contain">
                <label>交通工具：</label>
                <select class="transport" name="transportType" id="">
                    <option value="1">摩托车</option>
                    <option value="2">面包车</option>
                    <option value="3">小轿车</option>
                    <option value="4">公交地铁</option>
                    <option value="5">三轮车</option>
                </select>
            </div>
            <div class="ui-field-contain">
                <label>手机号：</label>
                <input type="text" id="phoneNum" name="phoneNum" class="phoneNum" value="" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
            </div>
            <div class="ConfirmCodeBox">
                <input class="ConfirmCode" type="text" name="code" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" data-role="none" />
                <div class="ApplyBtn">获取验证码</div>
            </div>
            <input type="button" class="submit-form" value="确认"/>
            <input type="button" class="refresh" value="重新上传照片">
        </div>
    </form>
    
</div>
</body>
<script>
    var button = null;
    function picJudge1 () {
        button = $('#button1');
        button.html("已选择");
        button.css({'text-shadow':'none','background-color':'#39D7C1','color':'white'});
    }
    function picJudge2 () {
        button = $('#button2');
        button.html("已选择");
        button.css({'text-shadow':'none','background-color':'#39D7C1','color':'white'});
    }
    function picJudge3 () {
        button = $('#button3');
        button.html("已选择");
        button.css({'text-shadow':'none','background-color':'#39D7C1','color':'white'});
    }
</script>
</html>