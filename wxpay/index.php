<?php
    session_start();
    $orderNo = $_GET['orderNo'];
    $money   = $_get['money'];
    session('orderNo',$orderNo);
    session('money',$money);
    echo "<script language=\"javascript\">";
    echo "document.location=\"./example/jsapi.php\"";
    echo "</script>";
?>