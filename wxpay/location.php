<?php
/**
 * Created by PhpStorm.
 * User: DeadSoul
 * Date: 2015/9/24
 * Time: 21:35
 */

$save = [];

$save ['pickupAddr'] = $_post['pickupAddr'];
$save ['sendAddr']   = $_POST['sendAddr'];
$save ['pickupTime'] = $_POST['pickupTime'];
$save ['weight']     = $_POST['weight'];
$save ['recipientName'] = $_POST['recipientName'];
$save ['recipientTel'] = $_POST['recipientTel'];
$save ['goodsDesc']     = $_POST['goodsDesc'];
$save ['transportType'] = $_POST['transportType'];
$save ['remark']        = $_POST['remark'];
$save ['payType']       = $_POST['payType'];

var_dump($save);