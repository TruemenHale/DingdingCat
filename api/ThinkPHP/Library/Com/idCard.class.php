<?php

/**
 * Created by PhpStorm.
 * User: DeadSoul
 * Date: 2016/1/19
 * Time: 11:38
 */
namespace Com;

class idCard {
    private $factor = array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
    private $city = array(11,12,13,14,15,21,22,23,31,32,33,34,35,36,37,41,42,43,44,45,46,50,51,52,53,54,61,62,63,64,65,71,81,82,91);
    private $pattern = '/^([\d]{17}[xX\d])$/';
    private $comparison = array(1,0,'x',9,8,7,6,5,4,3,2);

    public function check($idCard) {
        $idCard = strtolower($idCard);
        if (!preg_match($this->pattern,$idCard)) {
            return false;
        }
        $cityCode = substr($idCard,0,2);
        if (!in_array($cityCode,$this->city)) {
            return false;
        }
        $verify = substr($idCard,17,1);

        $checkEnd = $this->checkLastNum($idCard,$verify);
        if ($checkEnd) {
            return true;
        } else {
            return false;
        }
    }

    private function checkLastNum ($idCard,$verify) {
        $sum = 0;
        for ($i = 0;$i < 17;$i++) {
            $sum += (int)$idCard [$i] * (int)$this->factor [$i];
        }
        $parameter = $sum % 11;
        if ($this->comparison[$parameter] == $verify) {
            return true;
        } else {
            return false;
        }
    }
}