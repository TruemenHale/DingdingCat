<?php
    session_start();
    $orderNo = $_GET['orderNo'];
    $url = "../api/index.php?s=/Home/Order/orderToMoney";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    // post数据
    curl_setopt($ch, CURLOPT_POST, 1);
    // post的变量
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'orderNo='.$orderNo);
    $output = curl_exec($ch);
    curl_close($ch);
    $res = json_decode($output, TRUE);
    $money = $res['money'];
    echo $orderNo."\n";
    echo $money
?>