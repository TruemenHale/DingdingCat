<?php
    session_start();
    $orderNo = $_GET['orderNo'];
    $money = $_GET['money'];

    while ($res->fetch()) {
        echo $money;
        $_SESSION['money'] = $money;
    }
?>