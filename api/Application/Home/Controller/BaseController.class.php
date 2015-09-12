<?php
/**
 * Created by PhpStorm.
 * User: DeadSoul
 * Date: 2015/9/12
 * Time: 12:01
 */

namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller {
    public function _initialize()
    {
        if (!$this->checkMethodPost()) {
            $data = array(
                'status' => '-400',
                'info' => 'Bad Request Pls Use Method POST',
                'version' => '1.0'
            );
            $this->ajaxReturn($data);
        }
    }

    private function checkMethodPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function _empty(){
        $data = array(
            'status' => '-404',
            'info' => 'Not Found',
            'version' => '1.0'
        );
        $this->_cacheHeader();
        $this->ajaxReturn($data);
    }
}