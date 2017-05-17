<?php
/**
 * 在此做错误和异常统一处理
 */
class Controller_Index extends Controller_Abstract {
    public function params(){
        return array();
    }

    public function action(){
        $this->response['a'] = 'hello';
    }
}