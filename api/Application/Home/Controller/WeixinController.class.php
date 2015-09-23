<?php
/**
 * Created by PhpStorm.
 * User: DeadSoul
 * Date: 2015/9/20
 * Time: 15:25
 */

namespace Home\Controller;
use Com\Wechat;
use THink\Controller;

class WeixinController extends Controller {
    private $appId = "wx33717b4ef804de31";

    private $appSecret = '62d1a73eb4c3c141ac0758970d12906e';

    public function enter () {
        $token = "dingdingCat";

        $WeChat = new Wechat($token);

        $WeChat->response("text","test");
    }
}