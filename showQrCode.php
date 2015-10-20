<?php

include "qrcode/phpqrcode.php";

$openid = $_GET['openid'];


    $url = "http://wx.tyll.net.cn/DingdingCat/api/index.php?s=/Home/Order/sendNowOrder";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    // post数据
    curl_setopt($ch, CURLOPT_POST, 1);
    // post的变量
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'openid='.$openid);
    $output = curl_exec($ch);
    curl_close($ch);
    $res = json_decode($output, TRUE);
    if ($res['status'] == '0') {
        $order = $res ['order'];
        QRcode::png($order,false,"L","15");
    } else {
        echo "没有相关订单";
    }
