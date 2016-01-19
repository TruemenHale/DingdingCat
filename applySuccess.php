<?php
ini_set('date.timezone','Asia/Shanghai');
require_once "jssdk/jssdk.php";
$jsapi = new JSSDK("wxa3363e46c74608f3","52be407940dece37327465c1d211cfb4");
$signPackage = $jsapi->getSignPackage();
?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>叮叮猫</title>
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row text-center">
        <div class="col-sm-12"><h3 style="font-weight: 900">申请提交成功,请等待审核！</h3></div>
    </div>
    <div class="row text-center">
        <div class="col-sm-12"><h1><span style="color: #5cb85c">页面即将关闭</span></h1></div>
    </div>
</div>
</body>
<script>
    wx.config({
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: <?php echo $signPackage["timestamp"];?>,
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            'openLocation',
            'getLocation',
            'hideOptionMenu',
            'closeWindow'
        ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
    wx.ready(function(){
        wx.hideOptionMenu();
        setTimeout(function(){
            wx.closeWindow();
        },2000);
    });
    setTimeout(function(){
        var userAgent = navigator.userAgent;
        if (userAgent.indexOf("Firefox") != -1 || userAgent.indexOf("Chrome") !=-1) {
            window.location.href="about:blank";
        }else if(userAgent.indexOf('Android') > -1 || userAgent.indexOf('Linux') > -1){
            window.opener=null;window.open('about:blank','_self','').close();
        }else {
            window.opener = null;
            window.open("about:blank", "_self");
            window.close();
        }
    },3000);
</script>
</html>