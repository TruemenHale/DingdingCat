<?php
ini_set('date.timezone','Asia/Shanghai');
require_once "jssdk/jssdk.php";

$appid = "wxcb5b14c964fadb27";
$secret = "7cfbf146c18280d071d6e97a15f0acb7";

if (isset($_GET['code'])){
	$code = $_GET['code'];
}else{
	$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxcb5b14c964fadb27&redirect_uri=http%3a%2f%2fwx.tyll.net.cn%2fDingdingCat%2fbill.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
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
		<link rel="stylesheet" href="style/bill.css"/>
		<script src="js/bill.js"></script>
	</head>
	<body>
		<div data-role="page" id="MyBill">
			<div data-role="header" data-position="fixed">
				<h1>我的发票</h1>
			</div>
			<div role="main" class="ui-content" id="page">
				<p class="BillTitle" style="border-top: 1px solid #cccccc;">累计消费金额</p>
				<p class="BillNum" id="billTotal">0元</p>
				<p class="BillTitle">已开发票</p>
				<p class="BillNum" id="billUsed">0元</p>
				<p class="BillTitle">未开发票</p>
				<p class="BillNum" id="billUnUse">0元</p>
				<a href="#ApplyBill"><input type="button" value="申请发票"></a>
			</div>
		</div>
		<div data-role="page" id="ApplyBill">
			<div data-role="header" data-position="fixed">
				<a data-transition="none" href="#MyBill" data-role="button" data-inline="true" data-icon="back" data-iconpos="notext"></a>
				<h1>发票申请</h1>
			</div>
			<div role="main" class="ui-content" id="mainpage">
				<div class="ui-field-contain">
					<label>申请金额：</label>
					<input class="ApplyNum" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" type="text"/>
				</div>
				<div class="ui-field-contain">
					<label>发票收件地址：</label>
					<input class="getAddress"  type="text"/>
				</div>
				<div class="ui-field-contain">
					<input class="getAddress" type="button" value="提交"/>
				</div>
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