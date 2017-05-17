<?php
abstract class Controller_Api_Abstract extends \Base\Controller\Common {

    protected $responseFormatter = \S\Response::FORMAT_JSON;

    protected function beforeParams() {
        $this->checkAccessIp();
    }

    /**
     * 校验ip访问是否合法
     * @throws Exception
     */
    private function checkAccessIp() {
        if (\Core\Env::isProductEnv() && !\S\Util\Ip::isPrivateIp(\S\Util\Ip::getClientIp())) {
            \S\Log\Context::setInfo(array('非法ip', \S\Util\Ip::getClientIp(), $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']));
            throw new Exception('非法请求', 4000001);
        }
    }

}