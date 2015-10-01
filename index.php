<?php
    $jsKey = "m7RQzjA_ljjEkt-JCoklRLXP7SEo-gbvSxemPXafQj_OtIzyMtDp1Q2idJ1wR2rCo5FUta1My6u5n30FyE1UXA";

    $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $nonceStr = "";
    for ($i = 0 ;$i <16;$i++){
        $num = mt_rand(0,61);
        $nonceStr .= $str[$num];
    }


    $timestamp = time();

    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $key = "jsapi_ticket=$jsKey&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
    $signature = sha1($key);
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
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script src="js/main.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>
</head>
<body>

<!--代送页面-->
<div data-role="page" id="daisong">
    <div data-role="header"  data-position="fixed">
        <h1>代送</h1>
    </div>
    <div role="main" class="ui-content">
        <div class="ui-field-contain">
            <label>取件地址：</label>
            <input class="getAdress" name="pickupAddr" type="text"/>
            <div class="clearAddress">清除GPS定位地址</div>
        </div>
        <div class="ui-field-contain">
            <label>送达地址：</label>
            <input class="endAdress" name="sendAddr" type="text"/>
        </div>
        <div class="ui-field-contain">
            <label>取件时间：</label>
            <select class="getTime" name="pickupTime" id="">
                <option value="0">马上代送</option>
                <option value="1">半小时以后</option>
                <option value="2">预约其他时间</option>
            </select>
        </div>
        <div class="ui-field-contain">
            <label>物品重量：</label>
            <div class="numBox">
                <a class="minus" data-transition="none" href="" data-role="button" data-inline="true" data-icon="minus" data-iconpos="notext"></a>
                <input type="text" name="weight" data-role="none" class="KgNum" value="0.5">
                <a class="plus" data-transition="none" href="" data-role="button" data-inline="true" data-icon="plus" data-iconpos="notext"></a>
                <span style="margin-left:3px">Kg</span>
            </div>
        </div>
        <a href="#daisong2" data-transition="none"><input type="button" class="ui-btn-b" value="下一步"/></a>
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
                        <i class="iconfont">&#xe63b;</i><br>订单详情
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!--代送2页面-->
<div data-role="page" id="daisong2">
    <div data-role="header" data-position="fixed">
        <a data-transition="none" href="#daisong" data-role="button" data-inline="true" data-icon="back" data-iconpos="notext"></a>
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
            <label>其他备注：</label>
            <input class="note" name="remark" type="text"/>
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
            <label>付款方式：</label>
            <select class="payWays" name="payType" id="">
                <option value="0">微信支付</option>
                <option value="1">现金</option>
            </select>
        </div>
        <div class="ui-field-contain">
            <p>订单金额：<span class="money">12</span>元</p>
        </div>
        <input type="button" id="apply" value="提交订单"/>
        <div id="wxpay" style="display: none;">
            <form action="./wxpay/example/jsapi.php" method="post">
                <input type="hidden" name="money" id="wxpayMoney">
                <input type="hidden" name="orderNo" id="wxpayOrder">
                <input type="button" id="wxpayBtn" value="前去支付">
            </form>
        </div>

    </div>
</div>
<!--代购页面-->

<div data-role="page" id="daigou">
    <div data-role="header" data-position="fixed">
        <h1>代购</h1>
    </div>
    <div role="main" class="ui-content">
        <div class="ui-field-contain">
            <input type="text" onfocus="this.select();"  onmouseup="this.select();" value="test"  >
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
                        <i class="iconfont">&#xe63b;</i><br>订单详情
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!--详情页面-->

<div data-role="page" id="xiangqing">
    <div data-role="header" data-position="fixed">
        <h1>订单详情</h1>
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
                <td>代送</td>
                <td>CQ123456789</td>
                <td>2015年7月7日7：00</td>
                <td>花千骨</td>
                <td>18912345678</td>
                <td>长留山绝情殿一号厅</td>
                <td>蜀山大殿掌门书房书架上</td>
                <td>100公里</td>
                <td>1234</td>
                <td>2015年7月7日6:55</td>
                <td>2015年7月7日7:15</td>
                <td>2015年7月7日8:15前</td>
                <td>2015年7月7日8:10</td>
                <td>微信支付/未支付</td>
                <td>派送中</td>
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
                        <i class="iconfont">&#xe63b;</i><br>订单详情
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<script>
    wx.config({
        debug:true,
        appId: 'wx33717b4ef804de31', // 必填，公众号的唯一标识
        timestamp: <?php echo "'".$timestamp."'" ?>, // 必填，生成签名的时间戳
        nonceStr: <?php echo "'".$nonceStr."'"?>, // 必填，生成签名的随机串
        signature: <?php echo "'".$signature."'"?>,// 必填，签名，见附录1
        jsApiList: [
            'openLocation',
            'getLocation',
            'hideAllNonBaseMenuItem'
        ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
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
                    $.mobile.loading('hide');
                } else {
                    $.ajax({
                        type : 'POST',
                        url  : './api/index.php?s=/Home/Order/locationTrans',
                        data : 'lat='+latitude+'&lng='+longitude,
                        dataType : 'json',
                        error: function (request) {
                            alert('获取失败')
                        } ,
                        success : function (response) {
                            var location = response.location;
                            $(".getAdress").val(location);
                        }
                    })
                }

            }
        });
    });


</script>
</body>
</html>
