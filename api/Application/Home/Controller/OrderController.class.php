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
    public function shipForMe () {

    }

    public function buyForMe () {

    }

    public function locationTrans () {
        $lng = I('post.lng');
        $lat = I('post.lat');

        $trans = $this->lalTrans($lng,$lat);

        $url ="http://api.map.baidu.com/geocoder/v2/?ak=AqFXx3FQKGme9bkLhrW60i02&output=json&location=".$trans['lat'].",".$trans['lng'];
        $json = file_get_contents($url);
        $output = json_decode($json);
        $location = $output['result']['formatted_address'];
        $return = [
            'status' => '0',
            'info'   => 'success',
            'location' => $location
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
        $EARTH_RADIUS = 6378.137;
        $a = rad2deg($lat1)- rad2deg($lat2);
        $b = rad2deg($lng1) - rad2deg($lng2);
        $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos(rad2deg($lat1))*cos(rad2deg($lat2))*pow(sin($b/2),2)));
        $s = $s *$EARTH_RADIUS;
        $s = round($s * 10000) / 10;
        return $s;
    }

    private function getLal ($location) {

    }
}