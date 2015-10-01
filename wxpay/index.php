<?php
    session_start();
    $orderNo = $_GET['orderNo'];

    $pdo = new PDO("mysql:host=deadsoul.mysql.rds.aliyuncs;dbname=dingdingcat","dingdingcat","dingdingcat");
    $rs = $pdo -> query("SELECT * FROM orders WHERE orderNo = $orderNo");
    while($row = $rs -> fetch()) {
        print_r($row);
    }
    echo "订单:".$orderNo."\n";

?>