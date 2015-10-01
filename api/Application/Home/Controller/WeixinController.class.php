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
    private $msgType = null;
    private $content = null;
    private $openid  = null;
    private $event   = null;
    private $eventKey= null;

    public function enter () {
        $token = "dingdingCat";

        $wechat = new Wechat($token);

        $data = $wechat->request();

        if ($data && is_array($data)) {
            $this->MsgFormat($data);
            $this->Msg($wechat);
        }

        $wechat->response('test','text');
    }

    private function Msg ($wechat) {
        switch ($this->msgType) {
            case Wechat::MSG_TYPE_EVENT:
                switch ($this->event) {
                    case Wechat::MSG_EVENT_SUBSCRIBE:
                        $reply = "欢迎您关注叮叮猫，在这里你可以找人代送、代购，也可以自己成为跑腿小哥！";
                        $this->subNum();
                        $wechat->replyText($reply);
                        break;
                    case Wechat::MSG_EVENT_UNSUBSCRIBE:
                        $this->subNum(2);
                        break;
                    case Wechat::MSG_EVENT_CLICK:
                        $this->msgReply($this->eventKey);
                        break;
                    case Wechat::MSG_EVENT_SCAN:
                        $this->scan($this->eventKey);
                        break;
                    default:
                        $reply = "请按下方按钮进行相关操作";
                        $wechat->replyText($reply);
                        break;
                }
                break;
            case Wechat::MSG_TYPE_TEXT:
                $this->msgReply($this->content);
                break;
            default:
                $reply = "请按下方按钮进行相关操作";
                $wechat->replyText($reply);
                break;
        }
    }

    private function msgReply ($content) {
        if ($content == "userCenter") {
            $title = "个人中心";
            $desc  = "点击进入个人中心";
            $url   = "http://deadsoul.net/dingdingCat/usrCenter.html?".$this->openid;
        } else if ($content == "Bill") {
            $title = "发票申请";
            $desc  = "点击进入申请发票";
            $url   = "http://deadsoul.net/dingdingCat/bill.html?".$this->openid;
        } else if ($content == "Register") {
            $title = "注册";
            $desc  = "点击进入注册成为叮叮猫用户";
            $url   = "http://deadsoul.net/dingdingCat/register.html?".$this->openid;
        } else if ($content == "Order"){
            $title = "代送下单";
            $desc  = "点击进入发布代送消息";
            $url   = "http://deadsoul.net/dingdingCat/index.php?".$this->openid;
        }
        $new = [$title,$desc,$url];
        $this-response($new,Wechat::MSG_TYPE_NEWS);
    }

    private function scan ($key) {

    }

    private function MsgFormat ($data) {
        $this->msgType = $data['MsgType'];
        $this->content = $data['Content'];
        $this->event   = $data['Event'];
        $this->openid  = $data['FromUserName'];
        $this->eventKey= $data['EventKey'];
    }

    private function subNum ($type = 1) {
        $date = date("Y-m-d",time());
        $wechat = M('wechat');
        $verify = $wechat->where("date = '$date'")->find();
        if (!$verify) {
            $save = [
                'date' => $date,
                'plus' => 0,
                'minus'=> 0
            ];
            $wechat->add($save);
        }
        if ($type == 1) {
            $wechat->where("date = '$date'")->setInc('plus');
        } else {
            $wechat->where("date = '$date'")->setInc('minus');
        }
    }
}