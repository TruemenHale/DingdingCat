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

class ApiController extends BaseController {
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

    public function accessMsgSend () {
        $openid = I('post.openid');
        $order  = I('post.order');
        $weChat = new WechatAuth();
        $token = $this->tokenJudge();
        $weChat->tokenWrite($token);
        $remark = "点击详情，获取取件二维码";
        $url = "http://wx.tyll.net.cn/DingdingCat/showQrCode.php?order=".$order;
        $send['first'] = [
            "value" => "您好，您的订单已被接单，请耐心等待跑腿哥上门。。。",
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

        $res = $weChat->sendTemplate($openid,$template_id,$send,$url);
        $this->ajaxReturn($res);
    }

    public function getGoodMsgSend () {
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
}