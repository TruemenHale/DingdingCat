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

    public function enter () {
        $token = "dingdingCat";

        $wechat = new Wechat($token);

        $data = $wechat->request();

        $wechat->response('test','text');
    }
}