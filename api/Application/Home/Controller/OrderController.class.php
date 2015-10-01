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
                'info'   => 'Error'
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

        M('send')->add($save);
        $sendId = M('send')->getLastInsID();
        $string = new \Org\Util\String();
        $randNum = $string->randString(8,1);
        $orderNo = "S".time().$randNum;
        $order = [
            'orderNo'   => $orderNo,
            'type'      => 0,
            'orderTime' => time(),
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

    public function getMoney () {
        $pickupAddr = I('post.pickupAddr');
        $sendAddr   = I('post.sendAddr');
        $weight     = I('post.weight');

        $money = 19.00;

        $location1 = $this->locationToLal($pickupAddr);
        $location2 = $this->locationToLal($sendAddr);

        $distance = $this->getDistance($location1['lat'],$location1['lng'],$location2['lat'],$location2['lng']);

        if ($distance > 5000) {
            $a = floor($distance / 5000);
            $money += ($a * 10);
        }

        if ($weight > 5) {
            $b = ceil($weight - 5) ;
            $money += ($b * 5);
        }

        $return = [
            'status' => '0',
            'money'  => floor($money),
            'd'=>$distance,
        ];
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
}