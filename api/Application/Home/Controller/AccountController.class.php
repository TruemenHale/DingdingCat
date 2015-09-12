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

        $header = $this->inviteFind($invite);

        $save   = [
            'name'        => $name,
            'phone'       => $phone,
            'header'      => $header,
            'regTime'     => date("Y-m-d H-i-s",time()),
            'invite_code' => $this->inviteCode()
        ];

        M('user')->add($save);

        $return = [
            'status' => '0',
            'info'   => 'register success'
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
            $save = [
                'code'   => $code,
                'm_time' => time()
            ];
            $db->where("phone = '$phone'")->save($save);
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

    /**\
     * @param $inviteCode
     * @return bool
     * 查找介绍人是否存在
     */
    private function inviteFind ($inviteCode) {
        $res = M('user')->where("invite_code = '$inviteCode'")->find();
        if ($res) {
            return $res ['phone'];
        } else {
            return null;
        }
    }

    /**
     * @return string
     * 生成推荐码
     */
    private function inviteCode () {
        $str    = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ";
        $string = "";

        for ($i = 0;$i < 10;$i++) {
            $num     = mt_rand(0,61);
            $string .= $str [$num];
        }

        return $string;
    }

    private function registerCheck ($phone) {
        $res = M('user')->where("phone = '$phone'")->find();
        if ($res) {
            return false;
        } else {
            return true;
        }
    }
}