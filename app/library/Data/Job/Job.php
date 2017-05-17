<?php
namespace Data\Job;

use \Dao\Db\Job\Once as DbOnce;

class Job{

    /**
     * 添加定时任务
     * @param $app_name
     * @param $command
     * @param $note
     * @param $crontab
     * @param $retry
     * @param string $proposer 申请人
     * @return bool|int
     */
    public function addTimed($app_name, $command, $crontab, $note, $retry, $proposer = ""){
        $data = array(
            'app_name'  => $app_name,
            'command'   => trim($command),
            'note'      => $note,
            'proposer'  => $proposer ? : \S\Request::session('uname', "WEB"),
            'crontab'   => $crontab,
            'retry'     => $retry
        );
        return (new \Dao\Db\Job\Timed())->add($data);
    }

    /**
     * 添加一次性任务
     * @param $app_name
     * @param $command
     * @param $note
     * @param $retry
     * @param $force
     * @param $proposer
     * @return bool|int
     */
    public function addOnce($app_name, $command, $note, $retry, $force = \Dao\Db\Job\Once::FORCE_NO, $proposer = ""){
        $data = array(
            'app_name'  => $app_name,
            'command'   => trim($command),
            'note'      => $note,
            'retry'     => $retry,
            'force'     => $force,
            'proposer'  => $proposer ? : \S\Request::session('uname', "WEB"),
        );
        return (new DbOnce())->add($data);
    }

    /**
     * 获取任务信息
     * @param $job_id
     * @param $type
     * @return array
     */
    public function getJob($job_id, $type){
        if($type == \Dao\Db\Job\Log::TYPE_TIMED){
            $ret = (new \Dao\Db\Job\Timed())->get($job_id);
        }else{
            $ret = (new \Dao\Db\Job\Once())->get($job_id);
        }
        return $ret;
    }
}

