<?php
    $orderNo = $_GET['orderNo'];

    $mysqli = new mysqli();
    $mysqli->connect('deadsoul.mysql.rds.aliyuncs.com','dingdingcat','dingdingcat','dingdingcat');

    $res = $mysqli->prepare("SELECT * FROM orders WHERE orderNo = $orderNo");
    $res->execute();
    $res->bind_result($money);
    while ($res->fetch()) {
        echo $money;
        $_SESSION['money'] = $money;
    }
?>