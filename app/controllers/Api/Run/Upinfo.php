<?php
/**
 * 更新任务执行信息
 */
class Controller_Api_Run_Upinfo extends Controller_Api_Abstract {

    public function params(){
        return array(
            'run_id'    => array(
                'value' => \S\Request::post('run_id'),
                'rule'  => 'regx',
                'option' => array('regx' => '/^([A-Z0-9\-]){1,15}$/', 'error' => 'validate.run_id_error')
            ),
            'info'      => array(
                'value' => \S\Request::post('info'),
                'rule' => 'str',
                'option' => array('max' => '65535', 'error' => 'validate.job_info_error')
            ),
        );
    }

    public function action(){

        $run_id = ltrim($this->params['run_id'], "JOB-");
        $ret = (new \Data\Job\Log())->upInfo($run_id, $this->params['info']);
        $this->response['data'] = $ret;
    }
}