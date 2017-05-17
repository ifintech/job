<?php
//入口常量定义
define('APP_START_TIME', microtime(1));

//路径定义
define('PHPLIB',    '/data1/htdocs/phplib2');
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH.'/app');
define('APP_CONF',  ROOT_PATH.'/conf');
define('APP_VIEW',  APP_PATH.'/views');

// phpini定义
ini_set('yaf.library',          PHPLIB);
ini_set('yaf.use_spl_autoload', 'On');