<?php
ini_set('date.timezone','Asia/Shanghai');
require_once "jssdk/jssdk.php";

$appid = "wxa3363e46c74608f3";
$secret = "52be407940dece37327465c1d211cfb4";

if (isset($_GET['code'])){
  $code = $_GET['code'];
}else{
  $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa3363e46c74608f3&redirect_uri=http%3a%2f%2fwx.tyll.net.cn%2fDingdingCat%2fsuggestion.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
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
  <link rel="stylesheet" href="style/style.css"/>
  <link rel="stylesheet" href="js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css"/>
  <script>
    var openid = "<?php echo $openid; ?>";
  </script>
  <script src="js/jquery-2.1.4.min.js"></script>
  <script src="js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>
  <script src="js/suggestion.js"></script>
</head>
<body>

<!--代送页面-->
<div data-role="page" id="suggestion">
  <div class="header" data-role="header" data-position="fixed">
    <h1>建议</h1>
  </div>
  <div role="main" class="ui-content">
    <div class="ui-field-contain">
      <label>建议内容:</label>
      <textarea style="resize: none;" data-role="none" name="" class="content" cols="30" rows="10"></textarea>
    </div>
  </div>
  <div class="ui-field-contain">
    <label>建议分类：</label>
    <select class="type">
      <option value="0">意见建议</option>
      <option value="1">问题上报</option>
    </select>
  </div>
  <input type="button" value="提交建议" class="sApply"/>
</div>
</body>
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
</html>