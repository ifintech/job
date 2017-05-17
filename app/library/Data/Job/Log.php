<?php
namespace Data\Job;

use Dao\Db\Job\Log as DbJobLog;

class Log{

    /**
     * 查询任务最新执行的日志
     * @param $task_id
     * @return array
     */
    public function queryOnceLog($task_id){
        $log = (new DbJobLog())->getOneByJobId($task_id, \Dao\Db\Job\Log::TYPE_ONCE);
        if($log){
            $log['exec_info'] = $log['exec_info'] ? json_decode($log['exec_info'], true) : array();
        }

        return $log;
    }

    /**
     * 更新执行信息
     * @param $id
     * @param $exec_info
     * @return bool
     */
    public function upInfo($id, $exec_info){
        $db_task_log = new DbJobLog();
        $log = $db_task_log->getOne($id);

        $exec_data = $log['exec_info'] ? json_decode($log['exec_info'], true) : array();
        $exec_data[] = array(
            'time' => date("Y-m-d H:i:s"),
            'msg'  => $exec_info,
        );

        $data = array(
            'exec_info' => json_encode($exec_data)
        );

        return $db_task_log->up($id, $data);
    }
}