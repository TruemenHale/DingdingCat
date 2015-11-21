<?php
    ini_set('date.timezone','Asia/Shanghai');
    require_once "jssdk/jssdk.php";

    $appid = "wxa3363e46c74608f3";
    $secret = "52be407940dece37327465c1d211cfb4";

    if (isset($_GET['code'])){
        $code = $_GET['code'];
    }else{
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa3363e46c74608f3&redirect_uri=http%3a%2f%2fwx.tyll.net.cn%2fDingdingCat%2findex.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
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
    <script src="js/jquery.tmpl.min.js"></script>
    <script src="js/main.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>
</head>
<body>

<!--代送页面-->
<div data-role="page" id="daisong">
    <div data-role="header" data-position="fixed" class="header">
        <h1>代送</h1>
    </div>
    <div role="main" class="ui-content">
        <div class="ui-field-contain" style="margin-top: 14px">
            <label>选择寄件区域：</label>
            <input class="getAddress" id="getAddress" type="text"/><br/>
            <span class="clearAdd">清除定位区域</span>
            <label>详细地址：</label>
            <input class="GetdetAddress" type="text"/>
        </div>
        <div class="ui-field-contain">
            <label>选择送达区域：</label>
            <input class="endAddress" id="endAddress" type="text"/><br/>
            <label>详细地址：</label>
            <input class="EnddetAddress" type="text"/>
        </div>
        <div class="ui-field-contain">
            <label>物品重量：</label>
            <div class="numBox">
                <a class="minus" data-transition="none" href="" data-role="button" data-inline="true" data-icon="minus" data-iconpos="notext"></a>
                <input type="text" name="weight" data-role="none" class="KgNum" value="1">
                <a class="plus" data-transition="none" href="" data-role="button" data-inline="true" data-icon="plus" data-iconpos="notext"></a>
                <span style="margin-left:3px">Kg</span>
            </div>
        </div>
        <div class="ui-field-contain">
            <p>订单金额：<span class="money"></span>元(<span class="distance"></span>公里)</p>
        </div>
        <a href="#daisong2" data-transition="none"><input type="button" class="ui-btn-b" value="下一步" /></a>
    </div>
    <div data-role="footer" data-position="fixed">
        <div data-role="navbar">
            <ul>
                <li>
                    <a href="#daisong" data-transition="none" class="daisong ui-link ui-btn ui-btn-active">
                        <i class="iconfont">&#xe647;</i><br>代送
                    </a>
                </li>
                <li>
                    <a data-transition="none" href="#daigou" class="daigou">
                        <i class="iconfont">&#xe601;</i><br>代购
                    </a>
                </li>
                <li>
                    <a data-transition="none" href="#xiangqing" class="xiangqing">
                        <i class="iconfont">&#xe63b;</i><br>最新订单
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!--代送2页面-->
<div data-role="page" id="daisong2">
    <div data-role="header" data-position="fixed" class="header">
        <a class="button-return" data-transition="none" href="#daisong" data-role="button" data-inline="true" data-icon="back" data-iconpos="notext"></a>
        <h1>代送</h1>
    </div>
    <div role="main" class="ui-content">
        <div class="ui-field-contain">
            <label>收件人姓名：</label>
            <input class="geterName" type="text" name="recipientName" onkeyup="value=value.replace(/[^\u4E00-\u9FA5]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\u4E00-\u9FA5]/g,''))" />
        </div>
        <div class="ui-field-contain">
            <label>收件人电话：</label>
            <input class="geterPhone" type="text" name="recipientTel" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
        </div>
        <div class="ui-field-contain">
            <label>货物描述：</label>
            <input class="goodsNote" name="goodsDesc" type="text"/>
        </div>
        <div class="ui-field-contain">
            <label>运输工具：</label>
            <select class="transport" name="transportType" id="">
                <option value="0">不限</option>
                <option value="1">摩托车</option>
                <option value="2">面包车</option>
                <option value="3">小轿车</option>
                <option value="4">地铁</option>
            </select>
        </div>
        <div class="ui-field-contain">
            <label>取件时间：</label>
            <select class="getTime" name="pickupTime" id="">
                <option>马上代送</option>
                <option>预约其他时间</option>
            </select>
        </div>
        <div class="ui-field-contain">
            <label>付款方式：</label>
            <select class="payWays" name="payType" id="">
                <option value="0">微信支付</option>
                <option value="1">现金</option>
            </select>
        </div>

        <input type="button" id="apply" value="提交订单"/>
        <div id="daisongPay" style="display:none">
            <form action="./wxpay/example/jsapi.php" method="post" data-ajax="false">
                <input type="hidden" name="money" id="sendMoney" value="">
                <input type="hidden" name="orderNo" id="sendOrder" value="">
                <input type="submit" id="wxpayBtn" value="前去支付">
            </form>
        </div>
    </div>
</div>
<!--代购页面-->

<div data-role="page" id="daigou">
    <div data-role="header" data-position="fixed" class="header">
        <h1>代购</h1>
    </div>
    <div role="main" class="ui-content">
        <div class="ui-field-contain">
            <label>选择送货区域：</label>
            <input class="dgAddress" type="text"/><br/>
            <span class="clearAdd">清除定位区域</span>
            <label>详细地址：</label>
            <input class="sendDet" type="text"/>
        </div>
        <div class="ui-field-contain">
            <label>商品描述：</label>
			<textarea style="resize: none;" data-role="none" name="" class="dgDescribe" cols="25" rows="10"></textarea>
        </div>
        <div class="ui-field-contain">
            <label>商品最高价格上限：</label>
            <input class="dgCost moneyInput" type="text" data-role="none"/>
            <span style="font-size: 16px;display: inline-block;line-height: 50px;margin-left: 5px">元</span>
        </div>
        <div class="ui-field-contain">
            <label>跑腿小费：</label>
            <input class="dgPay moneyInput" type="text" data-role="none"/>
            <span style="font-size: 16px;display: inline-block;line-height: 50px;margin-left: 5px">元</span>
        </div>
        <input type="button" value="提交订单" id="dgApply" class="dgApply"/>
        <div id="daigouPay" style="display:none;">
            <form action="./wxpay/example/jsapi.php" method="post" data-ajax="false">
                <input type="hidden" name="money" id="buyMoney" value="">
                <input type="hidden" name="orderNo" id="buyOrder" value="">
                <input type="submit" id="wxpayBtn" value="前去支付">
            </form>
        </div>

    </div>
    <div data-role="footer" data-position="fixed">
        <div data-role="navbar">
            <ul>
                <li>
                    <a href="#daisong" data-transition="none" class="daisong">
                        <i class="iconfont">&#xe647;</i><br>代送
                    </a>
                </li>
                <li>
                    <a data-transition="none" href="#daigou" class="daigou">
                        <i class="iconfont">&#xe601;</i><br>代购
                    </a>
                </li>
                <li>
                    <a data-transition="none" href="#xiangqing" class="xiangqing">
                        <i class="iconfont">&#xe63b;</i><br>最新订单
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div data-role="page" id="AddressGet">
    <div data-role="header" data-position="fixed" class="header">
        <h1 class="selectTitle">取货区域</h1>
    </div>
    <div role="main" class="ui-content">
        <p class="addressLine">
            <input type="text" data-role="none" class="AddressInput">
            <a class="cancel">
                取消
            </a>
        </p>
        <ul data-role="listview" class="addressList">

        </ul>
    </div>
</div>

<!--详情页面-->

<div data-role="page" id="xiangqing">
    <div data-role="header" data-position="fixed" class="header">
        <h1>最新订单</h1>
    </div>
    <div role="main" class="ui-content">
        <table data-role="table" data-inset="true" data-mode="reflow" class="ui-responsive table-stroke info-list">
            <thead>
            <tr>
                <th>订单类型：</th>
                <th>订单号：</th>
                <th>下单时间：</th>
                <th>收件人姓名：</th>
                <th>收件人手机号：</th>
                <th>取件地址：</th>
                <th>送达地址：</th>
                <th>全程距离：</th>
                <th>跑腿哥ID：</th>
                <th>抢单时间：</th>
                <th>取件时间：</th>
                <th>预计送达时间：</th>
                <th>送达时间：</th>
                <th>支付方式及支付状态：</th>
                <th>订单状态：</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td id="type"></td>
                <td id="orderNo"></td>
                <td id="orderTime"></td>
                <td id="name"></td>
                <td id="tel"></td>
                <td id="pickAddr"></td>
                <td id="sendAddr"></td>
                <td id="distance"></td>
                <td id="runner"></td>
                <td id="getTime"></td>
                <td id="pickTime"></td>
                <td id="planTime"></td>
                <td id="endTime"></td>
                <td id="pay"></td>
                <td id="status"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div data-role="footer" data-position="fixed">
        <div data-role="navbar">
            <ul>
                <li>
                    <a href="#daisong" data-transition="none" class="daisong">
                        <i class="iconfont">&#xe647;</i><br>代送
                    </a>
                </li>
                <li>
                    <a data-transition="none" href="#daigou" class="daigou">
                        <i class="iconfont">&#xe601;</i><br>代购
                    </a>
                </li>
                <li>
                    <a data-transition="none" href="#xiangqing" class="xiangqing">
                        <i class="iconfont">&#xe63b;</i><br>最新订单
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<script id="place_list" type="text/x-jquery-tmpl">
    <li>
        <p class="add-name">${name}</p>
        <p class="add-area">${area}</p>
    </li>
</script>
<script>
    wx.config({
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: <?php echo $signPackage["timestamp"];?>,
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            'openLocation',
            'getLocation',
            'hideOptionMenu'
        ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
    wx.ready(function() {
        wx.hideOptionMenu();
    });
    $.mobile.loading('hide');
    wx.ready(function(){
        wx.getLocation({
            type: 'gcj02',
            success: function (res) {
                var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                if (res == null) {
                    alert('地理位置获取失败');
                } else {
                    $.ajax({
                        type : 'POST',
                        url  : './api/index.php?s=/Home/Order/locationTrans',
                        data : 'lat='+latitude+'&lng='+longitude,
                        dataType : 'json',
                        error: function (request) {
                            return 0;
                        } ,
                        success : function (response) {
                            var location = response.location;
                            $(".getAddress").val(location);
                            $(".dgAddress").val(location);
                            getToken = true;
                        }
                    })
                }

            }
        });
    });
</script>
</body>
</html>
