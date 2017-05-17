<?php
header('Pragma: no-cache', false);
require dirname(__DIR__) . '/conf/Init.php';
$app  = new \Yaf\Application(APP_CONF. '/admin.ini');
$app->bootstrap()->run();