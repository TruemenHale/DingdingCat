<?php
include "qrcode/phpqrcode.php";
$order = $_GET['order'];
QRcode::png($order,false,"L","15");

