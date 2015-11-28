<?php
/**
 * Created by PhpStorm.
 * User: DeadSoul
 * Date: 2015/11/29
 * Time: 3:24
 */

$url = "http://wx.tyll.net.cn/DingdingCat/api/index.php?s=/Home/Api/getJsTicket";
$output = file_get_contents($url);
$res = json_decode($output, TRUE);
$access_token = $res ['token'];
var_dump($output);
echo "\n";
var_dump($res);
echo "\n";
var_dump($access_token);