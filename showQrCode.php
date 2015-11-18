<?php
include "qrcode/phpqrcode.php";
$order = $_GET['order'];
QRcode::png($order,false,"H","30");