<?php
/**
 * 提交任务
 */
class Controller_Api_Job_Submit extends Controller_Api_Abstract {

    public function params(){
        return array(
            'app_name' => array('value' => \S\Request::post('appname'), 'rule' => 'regx',
                'option' => array('regx' => '/^([a-zA-Z0-9.]){1,128}$/', 'error' => 'validate.job_appname_error')),
            'command'  => array('value' => \S\Request::post('command'), 'rule' => 'str', 'option' => array('error' => 'validate.job_command_error')),
            'note'     => array('value' => \S\Request::post('note'),    'rule' => 'str', 'option' => array('min' => 1, 'error' => 'validate.job_note_error')),
            'force'    => array('value' => \S\Request::post('force') ?: \Dao\Db\Job\Once::FORCE_NO, 'rule' => 'regx', 'option' => array('regx' => '/^[0-9]$/', 'error' => 'validate.job_force_error')),
            'retry'    => array('value' => \S\Request::post('retry') ?: \Dao\Db\Job\Once::RETRY_NO, 'rule' => 'regx', 'option' => array('regx' => '/^[0-9]$/', 'error' => 'validate.job_retry_error')),
        );
    }

    public function action(){

        $job_id = (new \Data\Job\Job())->addOnce($this->params['app_name'], $this->params['command'], $this->params['note'], $this->params['retry'], $this->params['force']);

        $this->response['data'] = array(
            'job_id' => $job_id
        );
    }
}