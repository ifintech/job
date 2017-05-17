<?php
/**
 * cli模式下统一入口
 * 开启错误输出
 * 内存使用512M
 */
ini_set("display_errors", 1);
ini_set('memory_limit', '512M');
if(php_sapi_name() !== 'cli'){
    trigger_error('this run not cli', E_USER_ERROR);
}

require dirname(__DIR__).'/conf/Init.php';
$app  = new \Yaf\Application(APP_CONF. '/job.ini');
$app->bootstrap();
//解析路由
$job_class = str_replace('_', '\\', $argv[1]);
if(!class_exists($job_class)){
    trigger_error("$job_class is not exist", E_USER_ERROR);
}
\Core\Env::setCliClass($job_class);
//设置进程别名
cli_set_process_title("PHP_".strtoupper(APP_NAME)."_".strtoupper($argv[1]));
$job_obj = new $job_class;
$app->execute(array($job_obj, 'action'), array_slice($argv, 2));