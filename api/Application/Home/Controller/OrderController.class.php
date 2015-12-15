<?php
/**
 * Created by PhpStorm.
 * User: DeadSoul
 * Date: 2015/9/14
 * Time: 13:23
 */

namespace Home\Controller;
use Com\WechatAuth;
use Think\Controller;
use Think\Exception;
use Think\Model;

class OrderController extends BaseController {
    /**
     * 代送订单
     */
    public function shipAccept () {
        $info = I('post.');
        $phone = $info ['phone'];
        $save = $info;
        if ($phone != session('phone')) {
            $return = [
                'status' => '-10',
                'info'   => '账户不存在！'
            ];
            $this->ajaxReturn($return);
        } else {
            $save ['userId'] = session('userId');
        }

        if ($info ['pickupAddr'] == null || $info ['sendAddr'] == null || $info ['pickupTime'] == null || $info ['weight'] == null || $info ['recipientName'] == null || $info ['recipientName'] == null || $info ['goodsDesc'] == null) {
            $return = [
                'status' => '-100',
                'info'   => '请完整填写上述内容'
            ];
            $this->ajaxReturn($return);
        }

        $save ['weight'] = $info ['weight'];
        $money = $this->moneyCal($info['pickupAddr'],$info['sendAddr'],$info ['weight']);
        $lt1 = session("location1");
        $lt2 = session("location2");
        $save ['startLongitude'] = $lt1 ['lng'];
        $save ['endLongitude'] = $lt2 ['lng'];
        $save ['startLatitude'] = $lt1 ['lat'];
        $save ['endLatitude'] = $lt2 ['lat'];
        $save ['money']    = $money;
        $save ['distance'] = number_format($this->distance($info['pickupAddr'],$info['sendAddr']) / 1000,1);
        $save ['pickupAddr'] = $info ['pickupAddr'].$info ['GdetAddr'];
        $save ['sendAddr']   = $info ['sendAddr'].$info ['EdetAddr'];
        M('send')->add($save);
        $sendId = M('send')->getLastInsID();
        $string = new \Org\Util\String();
        $randNum = $string->randString(8,1);
        $orderNo = "S".time().$randNum;

        /*$coupon = session("couponNo");
        if ($coupon) {
            $this->useCoupon($orderNo);
        }*/

        $order = [
            'orderNo'   => $orderNo,
            'type'      => 0,
            'orderTime' => date('Y-m-d H-i-s',time()),
            'userId'    => session('userId'),
            'payStatus' => 0,
            'status'    => 0,
            'binCode'   => '111',
            'sendId'    => $sendId,
            'money'     => $money,
            'revenue'   => $this->revenue($money)
        ];
        M('orders')->add($order);
        $return = [
            'status' => '0',
            'info'   => 'success',
            'orderNo'=> $orderNo,
            'payType'=> $info['payType'],
            'money'  => $money
        ];
        $this->ajaxReturn($return);
    }

    /**
     *代购订单
     */
    public function buyAccept () {
        $info = I('post.');
        $phone = $info ['phone'];
        $save = $info;
        if ($phone != session('phone')) {
            $return = [
                'status' => '-10',
                'info'   => '账户不存在'
            ];
            $this->ajaxReturn($return);
        } else {
            $save ['userId'] = session('userId');
        }

        if ($info ['sendAddr'] == null || $info ['goodsDesc'] == null || $info ['priceLimit'] == null || $info ['runnerFee'] == null) {
            $return = [
                'status' => '-100',
                'info'   => '请完整填写上述内容'
            ];
            $this->ajaxReturn($return);
        }

        $save ['recipientTel'] = $phone;
        if (!is_null(session("userName"))){
            $save ['recipientName'] = session("userName");
        } else {
            $save ['recipientName'] = session("userNick");
        }
        $save ['sendAddr'] = $info ['sendAddr'].$info ['sendDet'];
        $location = $this->locationToLal($info ['sendAddr']);
        $save ['longitude'] = $location ['lng'];
        $save ['latitude']  = $location ['lat'];
        M('purchase')->add($save);

        $sendId = M('purchase')->getLastInsID();


        $string = new \Org\Util\String();
        $randNum = $string->randString(8,1);
        $orderNo = "B".time().$randNum;


        $order = [
            'orderNo'   => $orderNo,
            'type'      => 1,
            'orderTime' => date('Y-m-d H-i-s',time()),
            'userId'    => session('userId'),
            'payStatus' => 0,
            'status'    => 0,
            'binCode'   => '111',
            'sendId'    => $sendId,
            'money'     => $info ['runnerFee'],
            'revenue'   => $this->revenue($info ['runnerFee'])
        ];
        M('orders')->add($order);

        $return = [
            'status' => '0',
            'info'   => 'success',
            'orderNo'=> $orderNo,
            'money'  => $info ['runnerFee']
        ];
        $this->ajaxReturn($return);
    }

    /**
     * 通过经纬度转换为地点
     */
    public function locationTrans () {
        $lng = I('post.lng');
        $lat = I('post.lat');

        $trans = $this->lalTrans($lng,$lat);

        $url ="http://api.map.baidu.com/geocoder/v2/?ak=k2ynBN7eZTDr5ymYwnTj7IXm&output=json&location=".$trans['lat'].",".$trans['lng'];
        $json = file_get_contents($url);
        $output = json_decode($json,true);
        $location = $output['result']['formatted_address'];
        $return = [
            'status' => '0',
            'info'   => 'success',
            'location' => $location
        ];
        $this->ajaxReturn($return);
    }

    public function orderToMoney () {
        $orderNo = I('post.orderNo');
        $res = M('orders')->where("orderNo = '$orderNo'")->find();

        $this->ajaxReturn([
            'money' => $res['money']
        ]);
    }

    public function orderInfo () {
        $phone = I('post.phone');
        $orderNo = I('post.orderNo');

        if ($phone != session('phone')) {
            $return = [
                'status' => '-10',
                'info'   => 'Error'
            ];
            $this->ajaxReturn($return);
        } else {
            $userId = session('userId');
        }

        if ($orderNo) {
            $res = M('orders')->where("orderNo = '$orderNo'")->find();
        } else {
            $res = M('orders')->where("userId = '$userId'")->order('orderTime desc')->find();
        }

        if ($res) {
            $sendId = $res ['sendId'];
            if ($res ['type'] == "0") {
                $info = M('send')->where("id = '$sendId'")->find();
                $type = "代送";
            }else {
                $info = M('purchase')->where("id = '$sendId'")->find();
                $type = "代购";
            }
            $status = $res ['status'];
            switch ($status) {
                case "0":
                    $status = "待抢单";
                    break;
                case "1":
                    $status = "已抢单";
                    break;
                case "2":
                    $status = "已致电寄件人";
                    break;
                case "3":
                    $status = "已取件";
                    break;
                case "4":
                    $status = "正在运送途中";
                    break;
                case "5":
                    $status = "已收件";
                    break;
                case "6":
                    $status = "已取消";
                    break;
                default:
                    $status = "未知";
                    break;
            }
            if ($res ['payStatus'] == 0) {
                $payStatus = "未支付";
            } else if ($res ['payStatus'] == 1) {
                $payStatus = "已支付";
            } else {
                $payStatus = "支付失败";
            }
            if ($info ['payType'] == 0) {
                $payType = "微信支付";
            } else {
                $payType = "现金支付";
            }
            $return = [
                'status' => '0',
                'data'   => [
                    'type'      => $type,
                    'orderNo'   => $res ['orderNo'],
                    'orderTime' => $res ['orderTime'],
                    'name'      => $info ['recipientName'],
                    'tel'       => $info ['recipientTel'],
                    'pickAddr'  => $info ['pickupAddr'],
                    'sendAddr'  => $info ['sendAddr'],
                    'distance'  => $info ['distance']."公里",
                    'runner'    => $res ['runnerId'],
                    'getTime'   => $res ['getOrderTime'],
                    'pickTime'  => $res ['visitTime'],
                    'planTime'  => $res ['planTime'],
                    'endTime'   => $res ['endTime'],
                    'money'     => $res ['money'],
                    'status'    => $status,
                    'payType'   => $payType,
                    'payStatus' => $payStatus,
                    'isPay'     => $res ['payStatus']
                ]
            ];
            $this->ajaxReturn($return);
        } else {
            $this->ajaxReturn(['status' => -1]);
        }

    }

    /**
     * 计算代送价格
     */
    public function getMoney () {
        $pickupAddr = I('post.pickupAddr');
        $sendAddr   = I('post.sendAddr');
        $weight     = I('post.weight');

        $money = $this->moneyCal($pickupAddr,$sendAddr,$weight);

        $return = [
            'status' => '0',
            'info'   => 'success',
            'money'  => floor($money),
            'distance' => session("km")
        ];
        $this->ajaxReturn($return);
    }

    /**
     * 用户订单列表
     */
    public function orderList () {
        $phone = I('post.phone');
        if ($phone != session('phone')) {
            $return = [
                'status' => '-10',
                'info'   => 'Error'
            ];
            $this->ajaxReturn($return);
        } else {
            $userId = session('userId');
        }
        $send = M('orders')
            ->field("orders.orderNo,orders.orderTime,orders.money,send.pickupAddr,send.sendAddr,orders.status")
            ->where("orders.userId = '$userId' AND type = 0")
            ->join("send ON send.id = orders.sendId")
            ->order('orderTime desc')
            ->select();

        $buy = M('orders')
            ->field("orders.orderNo,orders.orderTime,orders.money,purchase.sendAddr,orders.status")
            ->where("orders.userId = '$userId' AND type = 1")
            ->join("purchase ON purchase.id = orders.sendId")
            ->order('orderTime desc')
            ->select();
        $i = 0;
        foreach ($send as $var) {
            $list [$i] = [
                'type'      => "送",
                'orderNo'   => $var ['orderNo'],
                'orderTime' => $var ['orderTime'],
                'money'     => $var ['money'],
                'status'    => $this->statusJudge($var ['status']),
                'pickupAddr'=> $var ['pickupAddr'],
                'sendAddr'  => $var ['sendAddr']
            ];
            $sendList [$i] = [
                'orderNo'   => $var ['orderNo'],
                'orderTime' => $var ['orderTime'],
                'money'     => $var ['money'],
                'status'    => $this->statusJudge($var ['status']),
                'pickupAddr'=> $var ['pickupAddr'],
                'sendAddr'  => $var ['sendAddr']
            ];
            $i++;
        }
        $j = 0;
        foreach ($buy as $var) {
            $list [$i] = [
                'type'      => "购",
                'orderNo'   => $var ['orderNo'],
                'orderTime' => $var ['orderTime'],
                'money'     => $var ['money'],
                'status'    => $this->statusJudge($var ['status']),
                'pickupAddr'=> "无",
                'sendAddr'  => $var ['sendAddr']
            ];
            $buyList [$j] = [
                'orderNo'   => $var ['orderNo'],
                'orderTime' => $var ['orderTime'],
                'money'     => $var ['money'],
                'status'    => $this->statusJudge($var ['status']),
                'pickupAddr'=> "无",
                'sendAddr'  => $var ['sendAddr']
            ];
            $j++;
            $i++;
        }

        foreach ($list as $var) {
            $time[] = $var ['orderTime'];
        }

        array_multisort($time,SORT_DESC,$list);

        $return = [
            'status' => '0',
            'all'  => $list,
            'send' => $sendList,
            'buy'  => $buyList
        ];
        $this->ajaxReturn($return);
    }

    /**
     * 用户最后使用过的地址
     */
    public function commonAddr () {
        $phone = I('post.phone');
        if ($phone != session('phone')) {
            $return = [
                'status' => '-10',
                'info'   => 'Error'
            ];
            $this->ajaxReturn($return);
        } else {
            $userId = session('userId');
        }

        $send = M('orders')
            ->field("orders.orderTime,send.sendAddr")
            ->where("orders.userId = '$userId' AND type = 0")
            ->join("send ON send.id = orders.sendId")
            ->order('orderTime desc')
            ->select();

        $buy = M('orders')
            ->field("orders.orderTime,purchase.sendAddr")
            ->where("orders.userId = '$userId' AND type = 1")
            ->join("purchase ON purchase.id = orders.sendId")
            ->order('orderTime desc')
            ->select();
        $i = 0;
        foreach ($send as $var) {
            $list [$i] = [
                'addr' => $var ['sendAddr'],
                'time' => $var ['orderTime']
            ];
            $i++;
        }
        foreach ($buy as $var) {
            $list [$i] = [
                'addr' => $var ['sendAddr'],
                'time' => $var ['orderTime']
            ];
            $i++;
        }

        foreach ($list as $var) {
            $time[$i] = $var ['time'];
        }

        array_multisort($time,SORT_DESC,$list);

        $list = array_slice($list,0,3);

        $return = [
            'status' => '0',
            'list'   => $list
        ];
        $this->ajaxReturn($return);
    }

    public function historyAddr () {
        $phone = I('post.phone');
        if ($phone != session('phone')) {
            $return = [
                'status' => '-10',
                'info'   => 'Error'
            ];
            $this->ajaxReturn($return);
        } else {
            $userId = session('userId');
        }
        $send = M('orders')
            ->field("orders.orderTime,send.sendAddr")
            ->where("orders.userId = '$userId' AND type = 0")
            ->join("send ON send.id = orders.sendId")
            ->order('orderTime desc')
            ->select();

        $buy = M('orders')
            ->field("orders.orderTime,purchase.sendAddr")
            ->where("orders.userId = '$userId' AND type = 1")
            ->join("purchase ON purchase.id = orders.sendId")
            ->order('orderTime desc')
            ->select();
        $i = 0;
        foreach ($send as $var) {
            $list [$i] = [
                'addr' => $var ['sendAddr'],
                'time' => $var ['orderTime']
            ];
            $i++;
        }
        foreach ($buy as $var) {
            $list [$i] = [
                'addr' => $var ['sendAddr'],
                'time' => $var ['orderTime']
            ];
            $i++;
        }

        foreach ($list as $var) {
            $time[$i] = $var ['time'];
        }

        array_multisort($time,SORT_DESC,$list);

        $list = array_slice($list,0,10);

        $return = [
            'status' => '0',
            'list'   => $list
        ];
        $this->ajaxReturn($return);
    }

    /**
     * 微信支付返回支付状态的判定
     * post发起在wxpay/example/notify.php
     */
    public function payStatus () {
        $orderNo = I('post.orderNo');
        $save = [
            'payStatus' => 1
        ];
        $orders = M("orders");
        $orders->where("orderNo = '$orderNo'")->save($save);
        $userId = $orders->where("orderNo = '$orderNo'")->getField("userId");
        $openid = M('user')->where("id = '$userId'")->getField("openid");
        $this->successMsgSend($openid,$orderNo);
        $this->runnerMsgSend($orderNo);
        $return = [
            'status' => '0'
        ];
        $this->ajaxReturn($return);
    }

    /**\
     * 地点建议，通过input输入获取相应建议地点
     */
    public function placeSuggestion () {
        header("Access-Control-Allow-Origin: *");
        $keyword = I("post.keyword");

        $str = "http://api.map.baidu.com/place/v2/suggestion?query=$keyword&region=132&output=json&ak=k2ynBN7eZTDr5ymYwnTj7IXm";

        $json = file_get_contents($str);
        $output = json_decode($json,true);

        $result = $output ['result'];

        $list = [];
        $i = 0;
        foreach ($result as $var) {
            $list [$i] ['name'] = $var ['name'];
            $list [$i] ['area'] = $var ['city'].$var ['district'];
            $i++;
        }

        $return = [
            'status' => '0',
            'info'   => 'success',
            'list'   => $list
        ];
        $this->ajaxReturn($return);
    }

    /**
     * 正在配送的订单
     */
    public function sendNowOrder () {
        $openid = I('openid');
        $Info = M('user')->where("openid = '$openid'")->find();

        if (!$Info) {
            $return = [
                'status' => '-6',
                'info'   => 'Account Not Found'
            ];
            $this->ajaxReturn($return);
        }

        $userId = $Info ['id'];

        $res = M('orders')->where("userId = '$userId' AND type = '1' AND status = '1'")->limit(1)->order('orderTime desc')->find();
        if ($res) {
            $orderNo = $res ['orderNo'];
            $return = [
                'status' => '0',
                'order'  => $orderNo
            ];
        } else {
            $return = [
                'status' => '-11'
            ];
        }
        $this->ajaxReturn($return);
    }
    /**
     * @param $lng
     * @param $lat
     * @return array
     * 地图经纬度转化
     */
    private function lalTrans ($lng,$lat) {
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng;
        $y = $lat;
        $z =sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta) + 0.0065;
        $lat = $z * sin($theta) + 0.006;
        return array('lng'=>$lng,'lat'=>$lat);
    }

    /**
     * @param $lat1
     * @param $lng1
     * @param $lat2
     * @param $lng2
     * @return float
     * 根据经纬度获取两点间距离
     */
    private function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000; //approximate radius of earth in meters
        $lat1 = ($lat1 * pi() ) / 180;
        $lng1 = ($lng1 * pi() ) / 180;

        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;

        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;

        return round($calculatedDistance);
    }

    /**
     * @param $location
     * @return array
     * 地点转经纬度
     */
    private function locationToLal ($location) {
        $url = "http://api.map.baidu.com/geocoder/v2/?ak=k2ynBN7eZTDr5ymYwnTj7IXm&output=json&city=重庆市&address=".$location;

        $json = file_get_contents($url);
        $output = json_decode($json,true);

        $arr = array(
            'lat' => $output['result']['location']['lat'],
            'lng' => $output['result']['location']['lng']
        );

        return $arr;
    }

    /**
     * @param $location1
     * @param $location2
     * @param $weight
     * @return float
     * 价格计算器
     */
    private function moneyCal ($location1,$location2,$weight) {
        $money = null;

        $distance = $this->distance($location1,$location2);

        $db = M('money');

        $res = $db->select();
        $money   = $res [0]['money'];
        $km      = $res [1]['money'];
        $kg      = $res [2]['money'];
        $unitkm   = $res [3]['money'];
        $unitkg   = $res [4]['money'];
        $startkm = $res [5]['money'];
        $startkg = $res [6]['money'];
        /**
         * 不超过起始距离($startkm)和起始重量($startkg)的价格为$money
         * 超过其实距离后每单位距离($unitkm)价格为($km)
         * 重量计算同距离
         */
        $a = ceil($distance / 1000);
        if ($a > $startkm) {
            $a = ceil($a - $startkm);
            $a = ceil($a / $unitkm);
            $money += ($a * $km);
        }

        $km = sprintf("%.2f",$distance/1000);
        session("km",$km);

        if ($weight > $startkg) {
            $b = ceil($weight - $startkg) ;
            $b = ceil($b / $unitkg);
            $money += ($b * $kg);
        }
        //$money = $this->coupon($money);
        return $money;
    }

    /**
     * @param string $location1
     * @param string $location2
     * @return float
     * 获取两地点距离
     */
    private function distance ($location1,$location2) {
        $location1 = $this->locationToLal($location1);
        session("location1",$location1);
        $location2 = $this->locationToLal($location2);
        session("location2",$location2);

        $distance = $this->getDistance($location1['lat'],$location1['lng'],$location2['lat'],$location2['lng']);

        return $distance;
    }

    /**
     * @param $status[订单状态]
     * @return string
     * 订单状态判定
     */
    private function statusJudge ($status) {
        switch ($status) {
            case "0":
                $status = "待抢单";
                break;
            case "1":
                $status = "已抢单";
                break;
            case "2":
                $status = "已致电寄件人";
                break;
            case "3":
                $status = "已取件";
                break;
            case "4":
                $status = "正在运送途中";
                break;
            case "5":
                $status = "已收件";
                break;
            case "6":
                $status = "已取消";
                break;
            default:
                $status = "未知";
                break;
        }
        return $status;
    }

    /**
     * @param $money
     */
    private function coupon ($money) {
        $user = session("userId");
        $now  = date("Y-m-d",time());
        $new  = $this->newJudge();

        if ($new == 1) {
            $res  = M('coupon')->field("coupon.money,couponNo")->where("coupon.useStartTime < '$now' AND coupon.useEndTime > $now AND usecoupon.customerId = '$user' AND coupon.minimum <= '$money' AND usecoupon.status = 0 AND coupon.area = 0")->join("usecoupon ON couponId = coupon.id")->order("coupon.money DESC")->find();
        } else {
            $res  = M('coupon')->field("coupon.money,couponNo")->where("coupon.useStartTime < '$now' AND coupon.useEndTime > $now AND usecoupon.customerId = '$user' AND coupon.minimum <= '$money' AND usecoupon.status = 0 ")->join("usecoupon ON couponId = coupon.id")->order("coupon.money DESC")->find();
        }

        if ($res) {
            $money = $money - $res ['money'];
            $couponNo = $res ['couponNo'];
            session(array('couponNo'=>$couponNo,'expire'=>1800));
        }

        return $money;
    }

    /**
     * @return int [0|1]
     * 获取用户最新订单
     */
    private function newJudge () {
        $user = session("userId");
        $res = M('orders')->where("userId = '$user'")->find();

        if ($res) {
            return 0;
        } else {
            return 1;
        }
    }

    private function useCoupon ($orderId) {
        $couponNo = session("couponNo");
        $user     = session("userId");

        $save = [
            'orderId' => $orderId,
            'status'  => 1
        ];

        M('usecoupon')->where("customerId = '$user' AND couponNo = '$couponNo'")->save($save);
    }

    /**
     * @param $openid
     * @param $order [订单号]
     */
    public function successMsgSend ($openid,$order) {
        $weChat = new WechatAuth();
        $token = $this->tokenJudge();
        $weChat->tokenWrite($token);
        $content = "支付成功！\n\n你的订单号为".$order."的订单已经成功支付，请等待跑腿哥接单！";
        $res = $weChat->sendText($openid,$content);
        $this->ajaxReturn($res);
    }

    /**
     * 订单接单后微信模板消息发送
     */
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

    /**
     * @param int|string $money
     * @return string
     * 通过订单价格计算跑腿哥收入
     */
    private function revenue ($money) {
        $db = M("sysconf");
        $tax = $db->where("syskey = 'tax'")->getField("val");
        $pct = $db->where("syskey = 'pct'")->getField("val");
        $revenue = ($money - $money * $pct - $tax);
        $revenue = number_format($revenue,2,".","");
        return $revenue;
    }

    /**
     * @param $order
     * @return bool
     * 订单支付成功后向跑腿哥发短信
     */
    private function runnerMsgSend ($order) {
        $res = M('orders')->where("orderNo = '$order'")->find();

        $sendId = $res ['sendId'];
        if ($res ['type'] == "0") {
            $info = M('send')->where("id = '$sendId'")->find();
            $addr = $info ['pickupAddr'];
        } else {
            $info = M('purchase')->where("id = '$sendId'")->find();
            $addr = $info ['sendAddr'];
        }

        $location = $this->locationToLal($addr);
        $url = "http://kdj.tyll.net.cn:8080/dingdingmao/runner/push/".$location['lng']."/".$location['lat']."/";
        file_get_contents($url);
        return true;
    }

    public function runnerTest () {
        $order = I("post.orderNo");
        try {
            $res = M('orders')->where("orderNo = '$order'")->find();
        } catch(\Exception $e) {
            return "First Error";
        }

        try {
            $sendId = $res ['sendId'];
            if ($res ['type'] == "0") {
                $info = M('send')->where("id = '$sendId'")->find();
                $addr = $info ['pickupAddr'];
            } else {
                $info = M('purchase')->where("id = '$sendId'")->find();
                $addr = $info ['sendAddr'];
            }
        } catch(\Exception $e) {
            return "Second Error";
        }


        try {
            $location = $this->locationToLal($addr);
            $url = "http://kdj.tyll.net.cn:8080/dingdingmao/runner/push/".$location['lng']."/".$location['lat']."/";
            $res =file_get_contents($url);
        } catch (\Exception $e) {
            return "Third Error";
        }
        echo $res;
    }
}