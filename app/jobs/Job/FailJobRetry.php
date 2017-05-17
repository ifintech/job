<?php
namespace Jobs\Job;
/**
 * 检查前五分钟内执行失败的job, 如果其可重试则重新注册一次性任务
 */
class FailJobRetry extends \Base\Jobs\Job{

    public function action($argv = array()){

        $db = new \Dao\Db();
        $data_job = new \Data\Job\Job();

        $query_time = date("Y-m-d H:i:s", strtotime("-5 minute"));
        $sql = "select * from job_log where status = ? and mtime >= ?";
        $fail_logs = $db::db()->qsql($sql,array(\Dao\Db\Job\Log::STATUS_FAIL, $query_time));
        foreach ($fail_logs as $log){
            $job_info = $data_job->getJob($log['job_id'], $log['type']);
            if($job_info['retry']){
                $data_job->addOnce($job_info['app_name'], $job_info['command'], "失败任务重试 原任务ID:{$job_info['id']}", \Dao\Db\Job\Once::RETRY_NO, \Dao\Db\Job\Once::FORCE_NO, "JOB_FailJobRetry");
            }
        }

        \S\Log\Logger::getInstance()->info(array("执行完成"));
    }
}