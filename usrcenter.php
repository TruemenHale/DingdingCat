<?php
ini_set('date.timezone','Asia/Shanghai');
require_once "jssdk/jssdk.php";

$appid = "wxcb5b14c964fadb27";
$secret = "7cfbf146c18280d071d6e97a15f0acb7";

if (isset($_GET['code'])){
	$code = $_GET['code'];
}else{
	$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxcb5b14c964fadb27&redirect_uri=http%3a%2f%2fwx.tyll.net.cn%2fDingdingCat%2fusrcenter.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
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
$nickname = $info_arr ['nickname'];
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
		var nickname = "<?php echo $nickname; ?>"
	</script>
	<script src="js/jquery-2.1.4.min.js"></script>
	<script src="js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>
	<link rel="stylesheet" href="style/usrcenter.css"/>
	<script src="./js/usrcenter.js"></script>
	<script src="./js/jquery.tmpl.min.js"></script>
</head>
<body>
<div data-role="page" id="usrcenter">
	<div data-role="header" data-position="fixed">
		<h1>个人中心</h1>
	</div>
	<div role="main" class="ui-content">
		<ul data-inset="true" data-role="listview" style="font-size: 14px;font-family: 'Microsoft Yahei'">
			<li>
				昵称：<span id="nickname"></span>
			</li>
			<li>
				姓名：<span id="name"></span>
			</li>
			<li>
				电话：<span id="phone"></span>
			</li>
			<li>
				最近地址:
				<ul data-inset="true" id="addrList" data-role="listview">
				</ul>
			</li>
			<li>我的积分：<span id="score"></span>分</li>
			<li>
				<a href="#Allorder">全部订单</a>
			</li>
		</ul>
	</div>
</div>
<div data-role="page" id="Allorder">
	<div data-role="header" data-position="fixed">
		<a data-transition="none" href="#usrcenter" data-role="button" data-inline="true" data-icon="back" data-iconpos="notext"></a>
		<h1>全部订单</h1>
	</div>
	<div data-role="navbar">
		<ul>
			<li>
				<a data-transition="none" href="#Allorder" class="xiangqing">
					全部订单
				</a>
			</li>
			<li>
				<a href="#Daisongorder" data-transition="none" class="daisong">
					代送
				</a>
			</li>
			<li>
				<a data-transition="none" href="#Daigouorder" class="daigou">
					代购
				</a>
			</li>
		</ul>
	</div>
	<ul data-inset="true" data-role="listview" id="allList" style="color: #333">
		
	</ul>
</div>
<div data-role="page" id="Daisongorder">
	<div data-role="header" data-position="fixed">
		<a data-transition="none" href="#usrcenter" data-role="button" data-inline="true" data-icon="back" data-iconpos="notext"></a>
		<h1>全部订单</h1>
	</div>
	<div data-role="navbar">
		<ul>
			<li>
				<a data-transition="none" href="#Allorder" class="xiangqing">
					全部订单
				</a>
			</li>
			<li>
				<a href="#Daisongorder" data-transition="none" class="daisong">
					代送
				</a>
			</li>
			<li>
				<a data-transition="none" href="#Daigouorder" class="daigou">
					代购
				</a>
			</li>
		</ul>
	</div>
	<ul data-inset="true" data-role="listview" id="sendList" style="color: #333">

	</ul>
</div>
<div data-role="page" id="Daigouorder">
	<div data-role="header" data-position="fixed">
		<a data-transition="none" href="#usrcenter" data-role="button" data-inline="true" data-icon="back" data-iconpos="notext"></a>
		<h1>全部订单</h1>
	</div>
	<div data-role="navbar">
		<ul>
			<li>
				<a data-transition="none" href="#Allorder" class="xiangqing">
					全部订单
				</a>
			</li>
			<li>
				<a href="#Daisongorder" data-transition="none" class="daisong">
					代送
				</a>
			</li>
			<li>
				<a data-transition="none" href="#Daigouorder" class="daigou">
					代购
				</a>
			</li>
		</ul>
	</div>
	<ul data-inset="true" data-role="listview" id="buyList" style="color: #333">

	</ul>
</div>
</body>
<script id="all_list" type="text/x-jquery-tmpl">
			<li class="ui-first-child ui-last-child">
					<h1 class="orderType" style="font-size: 25px">${type}</h1>
					<p>订单号：<span class="orderNum">${orderNo}</span></p>
					<p>下单时间：<span class="date">${orderTime}</span></p>
					<p>取件地址：<span class="fromWhere">${pickupAddr}</span></p>
					<p>送货地址：<span class="toWhere">${sendAddr}</span></p>
					<p class="ui-li-aside orderLi"><span class="Cost">${money}</span>￥<br><span class="PayState">已完成</span></p>
			</li>
</script>
<script id="send_list" type="text/x-jquery-tmpl">
			<li class="ui-first-child ui-last-child">
					<h1 class="orderType" style="font-size: 25px">送</h1>
					<p>订单号：<span class="orderNum">${orderNo}</span></p>
					<p>下单时间：<span class="date">${orderTime}</span></p>
					<p>取件地址：<span class="fromWhere">${pickupAddr}</span></p>
					<p>送货地址：<span class="toWhere">${sendAddr}</span></p>
					<p class="ui-li-aside orderLi"><span class="Cost">${money}</span>￥<br><span class="PayState">已完成</span></p>
			</li>
</script>
<script id="buy_list" type="text/x-jquery-tmpl">
			<li class="ui-first-child ui-last-child">
					<h1 class="orderType" style="font-size: 25px">购</h1>
					<p>订单号：<span class="orderNum">${orderNo}</span></p>
					<p>下单时间：<span class="date">${orderTime}</span></p>
					<p>送货地址：<span class="toWhere">${sendAddr}</span></p>
					<p class="ui-li-aside orderLi"><span class="Cost">${money}</span>￥<br><span class="PayState">已完成</span></p>
			</li>
</script>
<script id="addr_list" type="text/x-jquery-tmpl">
	<li class="ui-li-static ui-body-inherit ui-first-child">${addr}</li>
</script>
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

