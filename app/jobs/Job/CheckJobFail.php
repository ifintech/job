<?php
namespace Jobs\Job;
/**
 * 检查五分钟前执行失败job,记录错误日志
 */
class CheckJobFail extends \Base\Jobs\Job{
    public function action($argv = array()){

        $db = new \Dao\Db();
        $data_job = new \Data\Job\Job();

        $query_time = date("Y-m-d H:i:s", strtotime("-5 minute"));
        $sql = "select * from job_log where status = ? and mtime >= ?";
        $ret_fails = $db::db()->qsql($sql,array(\Dao\Db\Job\Log::STATUS_FAIL, $query_time));
        foreach ($ret_fails as $ret_fail){
            $job_info = $data_job->getJob($ret_fail['job_id'], $ret_fail['type']);
            \S\Log\Logger::getInstance()->error(array("error_message"=>"{$ret_fail['app_name']}的任务执行失败", "job_id" => $ret_fail['job_id'], $ret_fail['type'], "app_name" => $ret_fail['app_name'], "command" => $job_info['command']." ".$job_info['params'], "export" => $ret_fail['exec_report']));
        }

        \S\Log\Logger::getInstance()->info(array("执行完成"));
    }
}