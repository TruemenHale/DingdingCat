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
        $smsRes = $this->smsSend($phone,$code);

        if(!$smsRes) {
            $return = [
                'status' => '-1'
            ];
        } else {
            $return = [
                'status' => '0'
            ];
        }


        $this->ajaxReturn($return);
    }

    public function smsSend ($phone,$code) {

        /*$account = "mt6724";
        $password = "1e44n8";*/ //TODO 新地址

        $account = "mt4777";
        $password = "892357";

        $time = microtime();
        $time = explode(" ",$time);
        $time = $time [1] + $time [0];
        $time = number_format($time * 1000,0,".","");
        $timestamp = $time;
        $access_token = md5($timestamp.$password);
        $receive = $phone;
        $smscontent = "你的验证码为$code,有效期10分钟";
        $str = "account=$account&timestamp=$timestamp&access_token=$access_token&receiver=$receive&smscontent=$smscontent&extcode=0";
        $url = "http://121.42.11.93:8001/interface/sendSms";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
        $output = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($output, TRUE);
        if ($res['res_code'] == '0') {
            return true;
        } else {
            return false;
        }
    }
    public function smsSendTest () {

        $account = "mt6724";
        $password = "1e44n8";
        $code = "123456";
        $phone = "18883862521";
        $time = microtime();
        $time = explode(" ",$time);
        $time = $time [1] + $time [0];
        $time = number_format($time * 1000,0,".","");
        $timestamp = $time;
        $access_token = md5($timestamp.$password);
        $receive = $phone;
        $smscontent = "你的验证码为$code,有效期10分钟";
        $str = "account=$account&timestamp=$timestamp&access_token=$access_token&receiver=$receive&smscontent=$smscontent&extcode=0";
        $url = "http://121.42.11.93:8001/interface/sendSms";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
        $output = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($output, TRUE);
        if ($res['res_code'] == '0') {
            return true;
        } else {
            return false;
        }
    }

    public function register () {
        $phone  = I('post.phone');
        $name   = I('post.name');
        $code   = I('post.code');
        $invite = I('post.invite',"");
        $openid = I('post.openid');
        $nickname = I('post.nickname');
        $headImg = I('post.headimg');

        $res = $this->registerCheck($phone);
        if (!$res) {
            $return = [
                'status' => '-3',
                'info'   => '该手机号已经注册！'
            ];
            $this->ajaxReturn($return);
        }

        $res = $this->codeCheck($phone,$code);
        if (!$res) {
            $return = [
                'status' => '-2',
                'info'   => '验证码错误！'
            ];
            $this->ajaxReturn($return);
        }

        $referee = $invite;
        $this->referee($referee);
        $save   = [
            'name'     => $name,
            'nickName' => $nickname,
            'phone'    => $phone,
            'referee'  => $referee,
            'regTime'  => date("Y-m-d H-i-s",time()),
            'openid'   => $openid,
            'header'   => $headImg
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
            'unuse' => $Info ['invoiceTotal'] - $Info ['invoiceUsed'],
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
        $head  = I('post.head');

        if ($phone != session('phone')) {
            $return = [
                'status' => '-10',
                'info'   => 'Error'
            ];
            $this->ajaxReturn($return);
        }

        if ($head == null || $money == null || $addr == null) {
            $return = [
                'status' => '-100',
                'info'   => '请完整填写上述内容'
            ];
            $this->ajaxReturn($return);
        }

        $res = $this->checkBalance($phone,$money);

        if (!$res) {
            $return = [
                'status' => '-7',
                'info'   => '请填写正确金额'
            ];
            $this->ajaxReturn($return);
        }

        $save = [
            'userid'    => $res,
            'applyTime' => date("Y-m-d H-i-s",time()),
            'head'      => $head,
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
        session("userName",$Info['name']);
        session("userNick",$Info['nickName']);

        $return = [
            'status' => '0',
            'phone'  => $Info['phone'],
            'name'   => $Info['name'],
            'nickname'=>$Info['nickName'],
            'score'  => $Info['score'],
        ];

        $this->ajaxReturn($return);
    }

    public function runnerApply () {
        $phone = I("post.phone");
        $code  = I('post.code');
        $name  = I('post.name');
        $head  = I('post.head');
        $trans = I('post.transportType');
        $idCardNo = I('post.idCardNo');

        $res = $this->runnerCheck($phone);
        if (!$res) {
            $return = [
                'status' => '-3',
                'info'   => '改手机号已经注册！'
            ];
            $this->ajaxReturn($return);
        }

        $res = $this->codeCheck($phone,$code);
        if (!$res) {
            $return = [
                'status' => '-2',
                'info'   => '验证码错误！'
            ];
            $this->ajaxReturn($return);
        }

        $save = [
            'name' => $name,
            'phone'=> $phone,
            'transportType' => $trans,
            'idCardNo' => $idCardNo,
            'regTime' => date("Y-m-d H-i-s",time()),
            'header' => $head
        ];
        M('runner')->add($save);

        $return = [
            'status' => '0',
            'info'   => '注册成功！'
        ];
        $this->ajaxReturn($return);
    }

    public function suggestionApply () {
        $phone = I('post.phone');
        $content = I('post.content');
        $type = I('post.type');

        if ($phone != session('phone')) {
            $return = [
                'status' => '-10',
                'info'   => 'Error'
            ];
            $this->ajaxReturn($return);
        } else {
            $userId = session('userId');
        }

        if (is_null($content)) {
            $return = [
                'status' => '-12312',
                'info'   => '请填写内容'
            ];
            $this->ajaxReturn($return);
        }

        $save = [
            'content' => $content,
            'userId'  => $userId,
            'userType'=> '0',
            'submitTime' => date('Y-m-d H-i-s',time()),
            'type'    => $type
        ];
        M('suggestion')->add($save);
        $return = [
            'status' => '0',
            'info'   => 'success'
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

    private function runnerCheck ($phone) {
        $res = M('runner')->where("phone = '$phone'")->find();
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
        if ($money == 0) {
            return false;
        }

        $res = M('user')->where("phone = '$phone'")->find();

        $diff = ($res ['invoiceTotal'] - $res ['invoiceUsed']) - $money;

        if ($diff < 0) {
            return false;
        } else {
            $save = [
                'invoiceUsed' => $res ['invoiceUsed'] + $money
            ];
            M('user')->where("phone = '$phone'")->save($save);
            return $res ['id'];
        }
    }

    private function referee ($runnerId) {
        $money = M('sysconf')->where("syskey = 'referee'")->getField("val");
        $res = M('runner')->where("id = '$runnerId'")->find();

        if ($res) {
            $save ['accountSum'] = $res ['accountSum'] + $money;
            M('runner')->where("id = '$runnerId'")->save($save);
        }
        return true;
    }
}