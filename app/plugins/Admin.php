<?php

class Plugin_Admin extends \Base\Plugin\Base {

    public function dispatchLoopStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
        ini_set('session.name', APP_NAME);
        ini_set('session.save_handler', 'redis');

        //启用session
        $session_config = \S\Config::confServer('cache.redis.common');
        $session_path   = 'tcp://' . $session_config['host'] . ':' . $session_config['port'];
        if (!empty($session_config['auth'])) {
            // 这里是为了兼容aliyun的redis和普通redis
            $password = isset($session_config['user']) ? "{$session_config['user']}:{$session_config['auth']}" : $session_config['auth'];
            $session_path .= "?auth={$password}";
        }
        ini_set('session.save_path', $session_path);

        session_start();

        //注册记录admin访问日志
        register_shutdown_function(array(new Modules\Admin\Model\Data\Visitlog(), 'add'));
    }

    public function preDispatch(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
        parent::preDispatch($request, $response);
        \Yaf\Dispatcher::getInstance()->autoRender(true);
    }

}
