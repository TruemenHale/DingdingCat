<?php

$appid = "wxcb5b14c964fadb27";
$secret = "7cfbf146c18280d071d6e97a15f0acb7";

if (isset($_GET['code'])){
    $code = $_GET['code'];
}else{
    $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxcb5b14c964fadb27&redirect_uri=http%3a%2f%2fwx.tyll.net.cn%2fDingdingCat%2ftest.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
    header("Location:".$url);
}

$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$get_token_url);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
$res = curl_exec($ch);
curl_close($ch);
$json_obj = json_decode($res,true);
var_dump($json_obj);
//根据openid和access_token查询用户信息
$access_token = $json_obj['access_token'];
$openid = $json_obj['openid'];
echo $openid;
?>