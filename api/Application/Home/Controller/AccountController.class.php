<?php
/**
 * Created by PhpStorm.
 * User: DeadSoul
 * Date: 2015/9/11
 * Time: 23:59
 */

namespace Home\Controller;
use Think\Controller;

class AccountController extends BaseController {
    public function codeSend () {
        $phone = I('post.phone');

        $res = $this->phoneCheck($phone);
        if (!$res) {
            $return = [
                'status' => '-1',
                'info'   => 'Phone Number Error'
            ];
            $this->ajaxReturn($return);
        }

        $code = $this->makeCode($phone);
        $message = "你的验证码为$code,有效期10分钟";

        $url = "http://211.149.212.171:8001/MWGate/wmgw.asmx/MongateCsSpSendSmsNew?userId=MT0016&password=963147&pszMobis=$phone&pszMsg=$message&iMobiCount=1&pszSubPort=*&RptFlag=1";
        file_get_contents($url);

        $return = [
            'status' => '0'
        ];
        $this->ajaxReturn($return);
    }

    public function register () {
        $phone  = I('post.phone');
        $name   = I('post.name');
        $code   = I('post.code');
        $invite = I('post.invite');
        $openid = I('post.openid');

        $res = $this->registerCheck($phone);
        if (!$res) {
            $return = [
                'status' => '-3',
                'info'   => 'Phone Already Exist'
            ];
            $this->ajaxReturn($return);
        }

        $res = $this->codeCheck($phone,$code);
        if (!$res) {
            $return = [
                'status' => '-2',
                'info'   => 'Code Error'
            ];
            $this->ajaxReturn($return);
        }

        $header = $invite;

        $save   = [
            'name'     => $name,
            'phone'    => $phone,
            'header'   => $header,
            'regTime'  => date("Y-m-d H-i-s",time()),
            'openid'   => $openid
        ];

        M('user')->add($save);

        $return = [
            'status' => '0',
            'info'   => 'register success'
        ];
        $this->ajaxReturn($return);
    }

    public function billInfo () {
        $openid = I('post.openid');

        $Info = M('user')->where("openid = '$openid'")->find();

        if (!$Info) {
            $return = [
                'status' => '-6',
                'info'   => 'Account Not Found'
            ];
            $this->ajaxReturn($return);
        }

        session("phone",$Info['phone']);

        $data = [
            'total' => $Info ['invoiceTotal'],
            'used'  => $Info ['invoiceUsed'],
            'unuse' => $Info ['invoiceUnuse'],
            'phone' => $Info ['phone']
        ];

        $return = [
            'status' => '0',
            'info'   => 'success',
            'data'   => $data
        ];

        $this->ajaxReturn($return);
    }

    public function applyBill () {
        $phone = I('post.phone');
        $money = I('post.money');
        $addr  = I('post.addr');

        if ($phone != session('phone')) {
            $return = [
                'status' => '-10',
                'info'   => 'Error'
            ];
            $this->ajaxReturn($return);
        }

        $res = $this->checkBalance($phone,$money);

        if (!$res) {
            $return = [
                'status' => '-7',
                'info'   => 'Error'
            ];
            $this->ajaxReturn($return);
        }

        $save = [
            'userid'    => $res,
            'applyTime' => date("Y-m-d H-i-s",time()),
            'money'     => $money,
            'addr'      => $addr,
            'status'    => '0'
        ];
        M('invoice')->add($save);

        $return = [
            'status' => '0',
            'info'   => 'success'
        ];
        $this->ajaxReturn($return);
    }

    public function openidToUser () {
        $openid = I('post.openid');

        $Info = M('user')->where("openid = '$openid'")->find();

        if (!$Info) {
            $return = [
                'status' => '-6',
                'info'   => 'Account Not Found'
            ];
            $this->ajaxReturn($return);
        }

        session("phone",$Info['phone']);
        session("userId",$Info['id']);

        $return = [
            'status' => '0',
            'phone'  => $Info['phone']
        ];

        $this->ajaxReturn($return);
    }

    /**
     * @param $tel
     * @return bool
     * 在发送短信前正则检查手机号是否正确
     */
    private function phoneCheck ($tel) {
        $res = preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#',$tel);
        return $res ? true : false;
    }

    /**
     * @param $phone
     * @param $code
     * @return bool
     * 用于验证码的检验
     */
    private function codeCheck ($phone,$code) {
        $res = M('code')->where("phone = '$phone'")->find();
        if ($code == $res ['code']) {
            $now  = time();
            $diff = $now - $res ['m_time'];
            if ($diff > 600) {
                return false;
            } else {
                M('code')->where("phone = '$phone'")->delete();
                return true;
            }
        } else {
            return false;
        }
    }

    /**\
     * @param $phone
     * @return string
     */
    private function makeCode ($phone) {
        $code = "";

        for ($i = 0;$i < 4;$i++) {
            $num = mt_rand(0,9);
            $code .= $num;
        }
        $db  = M('code');

        $res = $db->where("phone = '$phone'")->find();

        if ($res) {
            $now  = time();
            $diff = $now - $res ['m_time'];
            if ($diff < 60) {
                $return = [
                    'status' => '-4',
                    'info'   => '两次获取间间隔一分钟'
                ];
                $this->ajaxReturn($return);
            } else {
                $save = [
                    'code'   => $code,
                    'm_time' => time()
                ];
                $db->where("phone = '$phone'")->save($save);
            }

        } else {
            $save = [
                'phone'  => $phone,
                'code'   => $code,
                'm_time' => time()
            ];
            $db->add($save);
        }

        return $code;
    }

    private function registerCheck ($phone) {
        $res = M('user')->where("phone = '$phone'")->find();
        if ($res) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param $phone
     * @param $money
     * @return bool
     * 判断是否可以开出支票
     */
    private function checkBalance ($phone,$money) {
        $res = M('user')->where("phone = '$phone'")->find();

        $diff = $res ['invoiceUnuse'] - $money;

        if ($diff < 0) {
            return false;
        } else {
            return $res ['id'];
        }
    }

}