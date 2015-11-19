<?php
	ini_set('date.timezone','Asia/Shanghai');
	require_once "jssdk/jssdk.php";

	$appid = "wxcb5b14c964fadb27";
	$secret = "7cfbf146c18280d071d6e97a15f0acb7";

	if (isset($_GET['code'])){
		$code = $_GET['code'];
	}else{
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxcb5b14c964fadb27&redirect_uri=http%3a%2f%2fwx.tyll.net.cn%2fDingdingCat%2fregister.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
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
	$get_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
	$info_json = file_get_contents($get_info_url);
	$info_res = json_decode($info_json,true);
	$info_arr = (array)$info_res;
	$nickname = $info_arr ['nickname'];
	$headImg  = $info_arr ['headimgurl'];
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
			var nickname = "<?php echo $nickname; ?>";
			var headImg = "<?php echo $headImg; ?>"
			alert(nickname);
			alert(headImg);
		</script>
		<script src="js/jquery-2.1.4.min.js"></script>
		<script src="js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>
		<script src="js/register.js"></script>
		<script src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>
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
	<script>
		wx.config({
			appId: '<?php echo $signPackage["appId"];?>',
			timestamp: <?php echo $signPackage["timestamp"];?>,
			nonceStr: '<?php echo $signPackage["nonceStr"];?>',
			signature: '<?php echo $signPackage["signature"];?>',
			jsApiList: [
				'hideAllNonBaseMenuItem'
			] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
		});
	</script>
</html>