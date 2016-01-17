<?php
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
</head>
<body>
<div class="container-fluid">
    <div class="row text-center">
        <div class="col-sm-12"><h3 style="font-weight: 900">订单支付成功</h3></div>
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
    $.mobile.loading('hide');
    wx.ready(function(){
        wx.hideOptionMenu();
        setTimeout(function(){
            wx.closeWindow();
        },3000);
    });
</script>
</html>