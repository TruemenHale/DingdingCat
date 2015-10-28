<?php
	ini_set('date.timezone','Asia/Shanghai');
	require_once "jssdk/jssdk.php";

	$appid = "wxcb5b14c964fadb27";
	$secret = "7cfbf146c18280d071d6e97a15f0acb7";
	$code = $_GET["code"];

	if (!$code) {
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri=http%3a%2f%2fwx.tyll.net.cn%2fDingdingCat%2ftest.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
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

	$errcode = $json_obj ['errcode'];

	if ($errcode) {
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri=http%3a%2f%2fwx.tyll.net.cn%2fDingdingCat%2findex.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
		header("Location:".$url);
	}
	//根据openid和access_token查询用户信息
	$access_token = $json_obj['access_token'];
	$openid = $json_obj['openid'];

	$jsapi = new JSSDK("wxcb5b14c964fadb27","7cfbf146c18280d071d6e97a15f0acb7");
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
		</script>
		<script src="js/jquery-2.1.4.min.js"></script>
		<script src="js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>
		<script src="js/register.js"></script>
		<link rel="stylesheet" href="style/register.css"/>
	</head>
	<body>
		<div data-role="page" id="register">
			<div data-role="header" data-position="fixed">
				<h1>注册</h1>
			</div>
			<div role="main" class="ui-content">
				<div class="ui-field-contain">
					<label>姓名：</label>
					<input class="usrName" type="text" onkeyup="value=value.replace(/[^\u4E00-\u9FA5]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\u4E00-\u9FA5]/g,''))" />
				</div>
				<div class="ui-field-contain">
					<label>手机号：</label>
					<input type="text" class="phoneNum" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
				</div>
				<div class="ui-field-contain">
					<label>邀请码：</label>
					<input class="invite" type="text" placeholder="如果没有，可以留空" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
				</div>
				<div class="ConfirmCodeBox">
					<input class="ConfirmCode" type="text" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" data-role="none" />
					<div class="ApplyBtn">获取验证码</div>
				</div>
				<input class="registerBtn" type="button" value="确认"/>
			</div>
		</div>
	</body>
</html>