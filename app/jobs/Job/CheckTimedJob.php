<?php
namespace Jobs\Job;
/**
 * 检查定时任务是否有漏执行
 * 每分钟执行
 */
class CheckTimedJob extends \Base\Jobs\Job{

    public function action($argv = array()){

        $db = new \Dao\Db();
        $check_time = date('Y-m-d H:i', strtotime("-1 minutes"));

        $timed_jobs = $db::db()->query("job_timed", array("status" => \Dao\Db\Job\Timed::STATUS_ABLE), array("id", "command", "params", "crontab"));
        foreach($timed_jobs as $job){
            //判断定时任务是否需要在前一分钟执行
            $ret = $this->compareTimeandCrontab($check_time, $job['crontab']);
            if($ret && !$this->checkExecLog($job['id'], $check_time)) {
                \S\Log\Logger::getInstance()->error(array("定时任务未执行", $job['id'], $job['command']));
            }
        }

        \S\Log\Logger::getInstance()->info(array("执行完成"));
    }

    /**
     * 检查定时任务在执行时间内有无执行日志
     * @param $job_id
     * @param $check_time
     * @return bool
     */
    private function checkExecLog($job_id, $check_time){
        $sql = "select id from job_log where job_id = ? and type = ? and exec_start_time >= ? and exec_start_time <= ? ";
        $ret = (new \Dao\Db())::db()->qsql($sql, array($job_id, \Dao\Db\Job\Log::TYPE_TIMED, $check_time.":00", $check_time.":59"));
        return $ret ? true : false;
    }

    /**
     * 将查询时间和任务的crontab字段进行对比, 看是否在当前时间需要执行该任务
     * 若任务需被执行,返回查询时间转化后数组(供生成键名使用)
     * @param $check_time
     * @param $job_crontab
     * @return bool
     */
    private function compareTimeandCrontab($check_time, $job_crontab){

        //时间格式化
        $trans_time = explode(" ", $check_time);
        $date = explode("-", $trans_time[0]); //年-月-日
        $time = explode(":", $trans_time[1]); //时:分:秒
        $query_crontab = array(
            '0' => $time['1'], //分
            '1' => $time['0'], //时
            '2' => $date['2'], //日
            '3' => $date['1'], //月
            '4' => date('w')   //星期几
        );

        //crontab格式化
        $task_crontab = explode(" ", $job_crontab);
        foreach($task_crontab as &$item){
            //分钟可能存在逗号的情况,例如1,4,7 代表在1分,4分和7分的时候都需要执行
            $item = explode(",", $item);
        }
        foreach($task_crontab[4] as &$weekday){ //crontab的周日可表示为0或7,统一为0
            $weekday = $weekday == 7 ? 0 : $weekday;
        }

        for($i = 0; $i < 5; $i++){
            if( $task_crontab[$i] !="*"  && !in_array($query_crontab[$i], $task_crontab[$i]) ){
                return false; //时间不匹配
            }
        }
        return true;
    }

}