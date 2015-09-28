<?php
    session_start();
    if(isset($_SESSION['money'])) {
        session_unset($_SESSION['money']);
    }
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>桃园立林</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <link rel="stylesheet" href="style.css"/>
    <script>
        function inputFloat(oInput) {
            if ('' != oInput.value.replace(/\d{1,}\.{0,1}\d{0,}/, '')) {
                oInput.value = oInput.value.match(/\d{1,}\.{0,1}\d{0,}/) == null ? '' : oInput.value.match(/\d{1,}\.{0,1}\d{0,}/);
            }
        }
        window.onload = function(){
            var oLogo = document.getElementById("logo");
            var oMoney = document.getElementById("moneyNum");
            var plus = document.getElementById("plus");
            var minus = document.getElementById("minus");
            var oContent = document.getElementsByClassName('content')[0];
            var oFooter = document.getElementsByClassName('footer')[0];
            if(window.screen.availHeight>571){
                oFooter.style.top = window.screen.availHeight-566+'px';
            }
            oContent.style.height = window.screen.availHeight+'px';
            oMoney.addEventListener('keydown',function(){
                inputFloat(this);
            });
            plus.addEventListener('touchstart',function(){
                oMoney.value++;
                oMoney.value = parseFloat(oMoney.value).toFixed(2);
            });
            minus.addEventListener('touchstart',function(){
                if(oMoney.value-1<0){
                    return;
                }
                oMoney.value--;
                oMoney.value = parseFloat(oMoney.value).toFixed(2);
            });
            oLogo.style.left = document.body.clientWidth-84+'px';
        }
    </script>
</head>
<body>
<div class="content">
    <div class="header">
        <div class="mask"></div>
        <img src="images/logo.png" id="logo">
    </div>
    <div class="dir">
        <h3>关于重庆儿童救助基金会</h3><br/>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;基金会的服务对象主要是福利机构孤残儿童、社会贫困家庭儿童、社会散居孤儿。通过“慈幼共创•助养工程”、“慈幼共创•助医工程”、“慈幼共创•爱心护航”、公益创投四大公益项目，为孩子们的成长带来持续的帮助和正向力量。
        <br><br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;我们希望通过努力让本会成为困境儿童走向未来的起点。
    </div>
    <div class="main">
        <br>
        <form action="http://wx.tyll.net.cn/ChildrensFund/example/jsapi.php/" method="post">
            <label>捐款金额</label><br>
            <div class="NumHolder">
                <div id="minus" style="border-right: none;border-top-left-radius:10px;border-bottom-left-radius:10px;">-</div><span class="cell">￥</span><input class="moneyNum" name="money" id="moneyNum" type="text" value="1.00"/><div id="plus" style="border-left: 1px solid rgb(229,92,17);border-top-right-radius:10px;border-bottom-right-radius:10px;margin-left: -1px">+</div>
            </div>
            <input type="submit" value="我要捐款" id="pay" style="height: 40px;"/>
        </form>
    </div>
    <div class="footer">
        Copyright©2015 桃园立林 版权所有
    </div>
</div>
</body>
</html>