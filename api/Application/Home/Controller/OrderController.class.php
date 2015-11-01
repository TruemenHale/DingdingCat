<?php
/**
 * Created by PhpStorm.
 * User: DeadSoul
 * Date: 2015/9/14
 * Time: 13:23
 */

namespace Home\Controller;
use Think\Controller;

class OrderController extends BaseController {
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

        if ($info ['weight'] < 5) {
            $save ['weight'] = 0;
        } else if ($info ['weight'] < 10) {
            $save ['weight'] = 1;
        } else if ($info ['weight'] < 15) {
            $save ['weight'] = 2;
        } else if ($info ['weight'] < 20) {
            $save ['weight'] = 3;
        } else {
            $save ['weight'] = 4;
        }
        $save ['money']    = $this->moneyCal($info['pickupAddr'],$info['sendAddr'],$info ['weight']);
        $save ['distance'] = number_format($this->distance($info['pickupAddr'],$info['sendAddr']) / 1000,1);
        $save ['pickupAddr'] = $info ['pickupAddr'].$info ['GdetAddr'];
        $save ['sendAddr']   = $info ['sendAddr'].$info ['EdetAddr'];
        M('send')->add($save);
        $sendId = M('send')->getLastInsID();
        $string = new \Org\Util\String();
        $randNum = $string->randString(8,1);
        $orderNo = "S".time().$randNum;
        $order = [
            'orderNo'   => $orderNo,
            'type'      => 0,
            'orderTime' => date('Y-m-d H-i-s',time()),
            'userId'    => session('userId'),
            'payStatus' => 0,
            'status'    => 0,
            'binCode'   => '111',
            'sendId'    => $sendId,
            'money'     => 0.01
        ];
        M('orders')->add($order);

        $return = [
            'status' => '0',
            'info'   => 'success',
            'orderNo'=> $orderNo,
            'payType'=> $info['payType']
        ];
        $this->ajaxReturn($return);
    }

    /**
     *
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

        $save ['recipientTel'] = $phone;
        if (!is_null(session("userName"))){
            $save ['recipientName'] = session("userName");
        } else {
            $save ['recipientName'] = session("userNick");
        }

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
            'money'     => 0.01
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

    public function locationTrans () {
        $lng = I('post.lng');
        $lat = I('post.lat');

        $trans = $this->lalTrans($lng,$lat);

        $url ="http://api.map.baidu.com/geocoder/v2/?ak=AqFXx3FQKGme9bkLhrW60i02&output=json&location=".$trans['lat'].",".$trans['lng'];
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

        if ($phone != session('phone')) {
            $return = [
                'status' => '-10',
                'info'   => 'Error'
            ];
            $this->ajaxReturn($return);
        } else {
            $userId = session('userId');
        }
        $res = M('orders')->where("userId = '$userId'")->order('orderTime desc')->find();
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
                    $status = "已取件";
                    break;
                case "3":
                    $status = "已收件";
                    break;
                case "4":
                    $status = "已取消";
                    break;
                default:
                    $status = "未知";
                    break;
            }
            if ($res ['payStatus'] == 0) {
                $payStatus = "未支付";
            } else {
                $payStatus = "已支付";
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
                    'status'    => $status,
                    'pay'       => $payType."/".$payStatus
                ]
            ];
            $this->ajaxReturn($return);
        } else {
            $this->ajaxReturn(['status' => -1]);
        }

    }

    public function payJudge () {
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
        $res = M('orders')->where("userId = '$userId'")->order('orderTime desc')->find();

    }

    public function getMoney () {
        $pickupAddr = I('post.pickupAddr');
        $sendAddr   = I('post.sendAddr');
        $weight     = I('post.weight');

        $money = $this->moneyCal($pickupAddr,$sendAddr,$weight);

        $return = [
            'status' => '0',
            'money'  => floor($money),
        ];
        $this->ajaxReturn($return);
    }

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

    public function paySuccess () {
        $orderNo = I('post.orderNo');
        $save = [
            'payStatus' => 1
        ];

        M('orders')->where("orderNo = '$orderNo'")->save($save);
        $return = [
            'status' => '0'
        ];
        $this->ajaxReturn($return);
    }

    public function placeSuggestion () {
        header("Access-Control-Allow-Origin: *");
        $keyword = I("post.keyword");

        $str = "http://api.map.baidu.com/place/v2/suggestion?query=$keyword&region=132&output=json&ak=AqFXx3FQKGme9bkLhrW60i02";

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
     */
    private function locationToLal ($location) {
        $url = "http://api.map.baidu.com/geocoder/v2/?ak=AqFXx3FQKGme9bkLhrW60i02&output=json&address=".$location;

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
     */
    private function moneyCal ($location1,$location2,$weight) {
        $money = 19.00;

        $distance = $this->distance($location1,$location2);

        if ($distance > 5000) {
            $a = floor($distance / 5000);
            $money += ($a * 10);
        }

        if ($weight > 5) {
            $b = ceil($weight - 5) ;
            $money += ($b * 5);
        }

        return $money;
    }

    /**
     * @param $location1
     * @param $location2
     * @return float
     */
    private function distance ($location1,$location2) {
        $location1 = $this->locationToLal($location1);
        $location2 = $this->locationToLal($location2);

        $distance = $this->getDistance($location1['lat'],$location1['lng'],$location2['lat'],$location2['lng']);

        return $distance;
    }

    private function statusJudge ($status) {
        switch ($status) {
            case "0":
                $status = "待抢单";
                break;
            case "1":
                $status = "已抢单";
                break;
            case "2":
                $status = "已取件";
                break;
            case "3":
                $status = "已收件";
                break;
            case "4":
                $status = "已取消";
                break;
            default:
                $status = "未知";
                break;
        }
        return $status;
    }


}