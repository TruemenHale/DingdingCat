<?php
ini_set('date.timezone','Asia/Shanghai');
require_once "jssdk/jssdk.php";

$appid = "wxa3363e46c74608f3";
$secret = "52be407940dece37327465c1d211cfb4";

if (isset($_GET['code'])){
    $code = $_GET['code'];
}else{
    $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa3363e46c74608f3&redirect_uri=http%3a%2f%2fwx.tyll.net.cn%2fDingdingCat%2frunnerTest.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
    header("Location:".$url);
}

$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$get_token_url);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
$res = curl_exec($ch);
curl_close($ch);
$json_obj = json_decode($res,true);
//根据openid和access_token查询用户信息
$access_token = $json_obj['access_token'];
$openid = $json_obj['openid'];
$get_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
$info_json = file_get_contents($get_info_url);
$info_res = json_decode($info_json,true);
$info_arr = (array)$info_res;
$headImg  = $info_arr ['headimgurl'];
$jsapi = new JSSDK("wxa3363e46c74608f3","52be407940dece37327465c1d211cfb4");
$signPackage = $jsapi->getSignPackage();

?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>叮叮猫</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <link rel="stylesheet" href="js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css"/>
    <script>
        var openid = "<?php echo $openid; ?>";
        var headImg = "<?php echo $headImg; ?>";
    </script>
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
    <form action="./api/index.php?s=/Home/Account/runnerTest" method="post" data-ajax="false">
        <div role="main" class="ui-content">
            <div class="ui-field-contain">
                <label>姓名：</label>
                <input class="usrName" type="text" onkeyup="value=value.replace(/[^\u4E00-\u9FA5]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\u4E00-\u9FA5]/g,''))" />
            </div>
            <div class="ui-field-contain">
                <label>身份证号：</label>
                <input type="text" class="idCard" />
            </div>
            <div class="ui-field-contain">
                <input type="text" name="add-reportName" id="display" placeholder="" value=""/>
                <button type="button"  onclick="img_upload.click()">选择图片</button>
                <input id="img_upload" name="img_upload" type="file" multiple="true" style="display: none" onchange="display.value=this.value">

            </div>
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
                <input type="text" id="phoneNum" class="phoneNum" value="" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
            </div>
            <div class="ConfirmCodeBox">
                <input class="ConfirmCode" type="text" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" data-role="none" />
                <div class="ApplyBtn">获取验证码</div>
            </div>
            <input class="registerBtn" type="button" value="确认"/>
        </div>
    </form>
</div>
<script>
    wx.config({
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: <?php echo $signPackage["timestamp"];?>,
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            'hideOptionMenu'
        ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
    wx.ready(function() {
        wx.hideOptionMenu();
    });
</script>
</body>
</html>