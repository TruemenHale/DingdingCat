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
    private $wechat  = null;

    public function enter () {
        $token = "dingdingCat";

        $wechat = new Wechat($token);

        $this->wechat = $wechat;
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
                    case Wechat::MSG_EVENT_TEMPLATE:
                        return ;
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
            default:      //TODO 留空，可扩展
                $reply = "暂时不支持文字以外的回复";
                $wechat->replyText($reply);
                break;
        }
    }

    private function msgReply ($content) {
        if ($content == "userCenter") {
            $news [0] = array (
                'Title' => '个人中心',
                'Description' => '点击进入个人中心',
                'Url' => "http://wx.tyll.net.cn/DingdingCat/usrcenter.php"
            );
        } else if ($content == "Bill") {
            $news [0] = array (
                'Title' => '发票申请',
                'Description' => '点击进入申请发票',
                'Url' => "http://wx.tyll.net.cn/DingdingCat/bill.php"
            );
        } else if ($content == "Register") {
            $news [0] = array (
                'Title' => '注册',
                'Description' => '点击进入注册成为叮叮猫用户',
                'Url' => "http://wx.tyll.net.cn/DingdingCat/register.php"
            );
        } else if ($content == "Order"){
            $news [0] = array (
                'Title' => '下单',
                'Description' => '点击进入进行下单',
                'Url' => "http://wx.tyll.net.cn/DingdingCat/index.php"
            );
        } else if ($content == "runnerApply") {
            $news [0] = array (
                'Title' => '跑腿哥报名',
                'Description' => '快来报名跑腿哥吧',
                'Url' => "http://wx.tyll.net.cn/DingdingCat/runnerApply.php"
            );
        } else if ($content == "Suggestion") {
            $news [0] = array (
                'Title' => '投诉建议',
                'Description' => '点击进入发表建议，让我们变得更好',
                'Url' => "http://wx.tyll.net.cn/DingdingCat/suggestion.php"
            );
        } else {
            $this->dynamicReply($content);
            return ;
        }
        $this->wechat->replyNews($news);
    }

    /**
     * @param $content
     * 动态回复
     */
    private function dynamicReply ($content) {
        $map = [
            'keyWord' => $content
        ];
        $res = M('reply')->where($map)->find();

        if ($res) {
            if ($res ['replyType'] == 1) {
                $reply = $res ['textReply'];
                $this->wechat->replyText($reply);
            } else {
                if ($res ['newsPicUrl']) {
                    $news [0] = array (
                        'Title' => $res ['newsTitle'],
                        'Description' => $res ['newsDescription'],
                        'PicUrl' => $res ['newsPicUrl'],
                        'Url' => $res ['newsUrl']
                    );
                } else {
                    $news [0] = array (
                        'Title' => $res ['newsTitle'],
                        'Description' => $res ['newsDescription'],
                        'Url' => $res ['newsUrl']
                    );
                }
                $this->wechat->replyNews($news);
            }
        }
        return ;
    }

    private function scan ($key) {

    }

    /**
     * @param $data
     * 数据格式化，赋值到全局变量，方便调用
     */
    private function MsgFormat ($data) {
        $this->msgType = $data['MsgType'];
        $this->content = $data['Content'];
        $this->event   = $data['Event'];
        $this->openid  = $data['FromUserName'];
        $this->eventKey= $data['EventKey'];
    }

    /**
     * @param int $type
     * 用于粉丝统计
     */
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