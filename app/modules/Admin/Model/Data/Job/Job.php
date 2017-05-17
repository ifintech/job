<?php
namespace Admin\Model\Data\Job;

use Admin\Model\Dao\Db\Job\Once as AdminDbOnceJob;
use Admin\Model\Dao\Db\Job\Timed as AdminDbTimedJob;
use Dao\Db\Job\Timed as DbTimedJob;

class Job {

    /**
     * 更新定时任务
     * @param $job_id
     * @param $app_name
     * @param $command
     * @param $crontab
     * @param $note
     * @param $retry
     * @return mixed
     */
    public function updateTimed($job_id, $app_name, $command, $crontab, $note, $retry){
        $data = array(
            'app_name' => $app_name,
            'command' => trim($command),
            'crontab' => trim($crontab),
            'note'    => $note,
            'retry'   => $retry
        );
        return (new \Dao\Db\Job\Timed())->up($job_id, $data);
    }

    /**
     * 更新定时任务状态
     * @param $id
     * @return bool
     */
    public function updateTimedStatus($id){
        $info = (new DbTimedJob())->get($id);
        $update_status = ($info['status'] == AdminDbTimedJob::STATUS_DISABLE ? AdminDbTimedJob::STATUS_ABLE : AdminDbTimedJob::STATUS_DISABLE);

        return (new \Dao\Db\Job\Timed())->up($id, array('status' => $update_status));
    }

    /**
     * 设置一次性任务调度为强制调度
     * @param $id
     * @return bool
     */
    public function setOnceForce($id){
        $ret = (new \Dao\Db\Job\Once)->up($id, array('force' => \Dao\Db\Job\Once::FORCE_YES));
        return $ret;
    }

    /**
     * 前端获取table数据(一次性任务)
     *
     * @param $app_name
     *
     * @return array
     */
    public function queryOnceJobDataTable($app_name){
        $result =  (new AdminDbOnceJob())->queryDataTable($app_name);
        return $result;
    }

    /**
     * 前端获取table数据(定时任务)
     * @param $app_name
     * @return mixed
     */
    public function queryTimedJobDataTable($app_name){
        $result =  (new AdminDbTimedJob())->queryDataTable($app_name);
        return $result;
    }

    /**
     * 获取正在执行的任务
     * @param $app_name
     * @return array
     */
    public function getExecJob($app_name){
        $data_job = new \Data\Job\Job();

        $ret = array();
        $job_logs = (new \Dao\Db\Job\Log())->getByAppNameAndStatus($app_name, \Dao\Db\Job\Log::STATUS_HANDLING);
        foreach ($job_logs as $log){
            $job_info = $data_job->getJob($log['job_id'], $log['type']);
            $ret[] = array_merge($log, array("command" => $job_info['command'], "note" => $job_info['note'], "proposer" => $job_info['proposer']));
        }

        return $ret;
    }
}

