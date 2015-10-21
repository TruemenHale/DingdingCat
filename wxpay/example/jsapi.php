<?php 
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";

//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);
session_start();
if(isset($_POST['money'])){
	$money = strip_tags(trim($_POST['money']));
	if(!is_numeric($money)) {
		echo '数据错误';
		return;
	}
} elseif (!isset($_POST['money']) && !isset($_SESSION['money'])) {
	header('location: http://wx.tyll.net.cn/ChildrensFund/');
}

if (!isset($_SESSION['money'])) {
	$_SESSION['money'] = $money;
}

if(isset($_POST['orderNo'])){
	$orderNo = $_POST['orderNo'];
} elseif (!isset($_POST['orderNo']) && !isset($_SESSION['orderNo'])) {
	header('location: http://wx.tyll.net.cn/DingdingCat/');
}

if (!isset($_SESSION['orderNo'])) {
	$_SESSION['orderNo'] = $orderNo;
}
//①、获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenid();
$money = $_SESSION['money'];
$orderNo = $_SESSION['orderNo'];
$money = sprintf("%.2f", $money);
//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody("叮叮猫");
$input->SetAttach("叮叮猫");
$input->SetOut_trade_no($orderNo);
$input->SetTotal_fee($money*100);//*100
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url('http://wx.tyll.net.cn/DingdingCat/wxpay/example/notify.php');
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
$jsApiParameters = $tools->GetJsApiParameters($order);

//获取共享收货地址js函数参数
//$editAddress = $tools->GetEditAddressParameters();

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>叮叮猫</title>
    <script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				if(res.err_msg != "get_brand_wcpay_request:ok") {
					alert('支付好像出了小问题, 请稍后再试T^T');
//					alert(res.err_msg);
				} else {
					alert('支付成功~');
					window.location.href='http://wx.tyll.net.cn/DingdingCat';
				}
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall);
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	</script>
	<!-- 新 Bootstrap 核心 CSS 文件 -->
	<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>
	<div class="container-fluid">
		<div class="row text-center" style="margin-top: 50px">
			<div class="col-sm-12"><h3 style="font-weight: 900">您即将为</h3></div>
		</div>
		<div class="row text-center">
			<div class="col-sm-12"><h3 style="font-weight: 900"><?php echo $orderNo ?>订单付款</h3></div>
		</div>
		<div class="row text-center">
			<div class="col-sm-12"><h1><span style="color: #5cb85c"><?php echo $money ?></span>元</h1></div>
		</div>
		<div class="row text-center" style="margin-top: 180px;margin-left: 30px;margin-right: 30px">
			<div class="col-sm-12"><button class="btn btn-lg btn-block btn-warning" onclick="callpay()">立即支付</button></div>
		</div>
	</div>
</body>
</html>