<?php
/**
 * Created by PhpStorm.
 * User: DeadSoul
 * Date: 2015/11/22
 * Time: 1:39
 */

namespace Home\Controller;
use Com\WechatAuth;
use Think\Controller;

class ApiController extends Controller {
    public function getToken () {
        $res = M('token')->where("id = 1")->find();
        $time = $res ['m_time'];
        $now = time();

        if (($now - $time) >= 3600) {
            $weChat = new WechatAuth();
            $data = $weChat->getAccessToken();
            $token = $data ['access_token'];
            $save = [
                'token' => $token,
                'm_time' => time()
            ];
            M('token')->where("id = 1")->save($save);
        } else {
            $token = $res ['token'];
        }
        $return = [
            'status' => '0',
            'token'  => $token
        ];
        $this->ajaxReturn($return);
    }
}