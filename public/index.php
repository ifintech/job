<?php
/**
 * 应用服务入口
 *
 * 流程:
 * 1. 声明项目关键目录绝对路径的宏定义
 * 2. 加载应用配置, 内容参考: http://yaf.laruence.com/manual/yaf.config.html
 * 3. 使用bootstrap引导服务进行启动项的加载, 参考: http://yaf.laruence.com/manual/yaf.bootstrap.html
 */
header('Pragma: no-cache', false);
require dirname(__DIR__) . '/conf/Init.php';
$app  = new \Yaf\Application(APP_CONF. '/application.ini');
$app->bootstrap()->run();