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
    private function getToken () {
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

    public function getJsTicket () {
        $res = M('token')->where("id = 2")->find();
        $time = $res ['m_time'];
        $now = time();

        if (($now - $time) >= 3600) {
            $access_token = $this->getToken();
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$access_token";
            $res = json_decode(file_get_contents($url));
            $token = $res->ticket;
            $save = [
                'token' => $token,
                'm_time' => time()
            ];
            M('token')->where("id = 2")->save($save);
        } else {
            $token = $res ['token'];
        }
        $return = [
            'status' => '0',
            'token'  => $token
        ];
        $this->ajaxReturn($return);
    }

    /**
     * 订单接单后微信模板消息发送
     */
    public function accessMsgSend () {
        $openid = I('post.openid');
        $order  = I('post.order');
        $runnerInfo = $this->orderToRunner($order);
        $runnerName = $runnerInfo ['name'];
        $runnerPhone = $runnerInfo ['phone'];
        $weChat = new WechatAuth();
        $token = $this->tokenJudge();
        $weChat->tokenWrite($token);
        $remark = "点击详情，获取取件二维码";
        $url = "http://wx.tyll.net.cn/DingdingCat/showQrCode.php?order=".$order;
        $send['first'] = [
            "value" => "您好，您的订单已被跑腿哥-$runnerName($runnerPhone)接单，请耐心等待跑腿哥上门。。。",
            "color" => "#173177"
        ];
        $send['keyword1'] = [
            "value" => $order,
            "color" => "#173177"
        ];
        $send['keyword2'] = [
            "value" => date("Y-m-d H:i:s",time()),
            "color" => "#173177"
        ];
        $send['remark'] = [
            "value" => $remark,
            "color" => "#173177"
        ];
        $template_id = "lXVgtPiYy21dQ88efpZN6PDqefamoEXrJDPP8zovPXU";

        $res = $weChat->sendTemplate($openid,$template_id,$send,$url);
        $this->ajaxReturn($res);
    }

    private function orderToRunner ($order) {
        $res = M('orders')->field("runner.name,runner.phone")->where("orders.orderNo = '$order'")->join("runner ON runner.id = orders.runnerId")->find();
        return $res;
    }

    public function finishMsgSend () {
        $openid = I('post.openid');
        $order  = I('post.order');
        $weChat = new WechatAuth();
        $token = $this->tokenJudge();
        $weChat->tokenWrite($token);
        $remark = "感谢使用叮叮猫跑腿！";
        $send['first'] = [
            "value" => "您好，您的订单已成功送达！",
            "color" => "#173177"
        ];
        $send['keyword1'] = [
            "value" => $order,
            "color" => "#173177"
        ];
        $send['keyword2'] = [
            "value" => date("Y-m-d H-i-s",time()),
            "color" => "#173177"
        ];
        $send['remark'] = [
            "value" => $remark,
            "color" => "#173177"
        ];
        $template_id = "lXVgtPiYy21dQ88efpZN6PDqefamoEXrJDPP8zovPXU";

        $res = $weChat->sendTemplate($openid,$template_id,$send);
        $this->ajaxReturn($res);
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