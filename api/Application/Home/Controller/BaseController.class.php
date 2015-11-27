<?php
/**
 * Created by PhpStorm.
 * User: DeadSoul
 * Date: 2015/9/12
 * Time: 12:01
 */

namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller {
    public function _initialize()
    {
        if (!$this->checkMethodPost()) {
            $data = array(
                'status' => '-400',
                'info' => 'Bad Request Pls Use Method POST',
                'version' => '1.0'
            );
            $this->ajaxReturn($data);
        }
    }

    private function checkMethodPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function _empty(){
        $data = array(
            'status' => '-404',
            'info' => 'Not Found',
            'version' => '1.0'
        );
        $this->_cacheHeader();
        $this->ajaxReturn($data);
    }

    protected function tokenJudge () {
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
        return $token;
    }
}