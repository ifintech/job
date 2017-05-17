<?php
namespace Jobs\Daemon;
/**
 * Class Master
 *
 * @package     Jobs\Daemon
 * @description 守护进程主进程
 *
 *              备注:
 *              1. 守护进程启动入口;
 *              2. 启动|重启命令示范: nohup /usr/bin/php /data1/htdocs/demo.com/jobs/job.php Jobs_Daemon_Master >> /data2/logs/demo.com/daemon.log 2>&1 &
 *              3. 重启命令和启动命令相同;
 */
class Master extends \Base\Jobs\Job {

    public function action($argv = array()) {
        $master = new \S\Thread\Master();

        //设置工作进程的配置
        $config = new \S\Thread\Config();
        $config->setWorkerConfig("\\Jobs\\Daemon\\Demo\\Worker", 1);

        $master->main();
    }

}