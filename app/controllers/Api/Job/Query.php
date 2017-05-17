<?php
/**
 * 查询一次性任务执行情况
 */
class Controller_Api_Job_Query extends Controller_Api_Abstract {

    public function params(){
        return array(
            'job_id' => array('value' => \S\Request::post('job_id'), 'rule' => 'regx',
                           'option' => array('regx' => '/^([0-9]){1,10}$/', 'error' => 'validate.job_id_error')),
        );
    }

    public function action(){

        $log = (new \Data\Job\Log())->queryOnceLog($this->params['job_id']);
        if($log){
            $log = array(
                'exec_start_time' => $log['exec_start_time'],
                'exec_end_time'   => $log['exec_end_time'],
                'exec_info'       => $log['exec_info'],
                'exec_export'     => $log['exec_export'],
                'status'          => $log['status'],
                'run_id'          => "JOB-".$log['id'],
            );
        }else{
            $log = array(
                'exec_start_time' => "0000-00-00 00:00:00",
                'exec_end_time'   => "0000-00-00 00:00:00",
                'exec_info'       => array(),
                'exec_export'     => "",
                'status'          => -1,
                "run_id"          => "",
            );
        }

        $this->response['data'] = $log;
    }
}