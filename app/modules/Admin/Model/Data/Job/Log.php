<?php
namespace Admin\Model\Data\Job;

class Log{

    public function queryDataTable($task_id, $type){
        $result = (new \Admin\Model\Dao\Db\Job\Log())->queryDataTable($task_id,$type);
        return $result;
    }
}