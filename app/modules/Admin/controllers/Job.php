<?php
/**
 * @name  任务管理
 */
use Data\Job\Job;
use Admin\Model\Data\Job\Job as AdminDataJob;

class Controller_Job extends \Modules\Admin\Controllers\Controller {

    /**
     * @name 定时任务展示页面
     */
    public function timedAction(){
        $app_name = $this->getParams('app_name');
        $this->response['app_name'] = $app_name;
    }

    /**
     * @name 一次性任务展示页面
     */
    public function onceAction(){
        $app_name = $this->getParams('app_name');
        $this->response['app_name'] = $app_name;
    }

    /**
     * @name 定时任务列表数据源
     */
    public function timedTableAction(){
        $app_name = $this->getParams('app_name');
        $data = (new AdminDataJob())->queryTimedJobDataTable($app_name);

        $this->response = $data?:array();
    }

    /**
     * @name 一次性任务数据源
     */
    public function onceTableAction(){
        $app_name = $this->getParams('app_name');
        $data = (new AdminDataJob())->queryOnceJobDataTable($app_name);
        $this->response = $data?:array();
    }

    /**
     * @name 添加定时任务
     */
    public function addTimedAction(){
        $id = $this->getParams('job_id');
        $app_name = $this->getParams('app_name');
        $command = $this->getParams('command');
        $crontab = $this->getParams('crontab');
        $note = $this->getParams('note');
        $retry = $this->getParams('retry');

        if($id){
            (new AdminDataJob())->updateTimed($id, $app_name, $command, $crontab, $note, $retry);
        }else{
            (new Job())->addTimed($app_name, $command, $crontab, $note, $retry);
        }
        $this->response['msg'] = '添加成功';

    }

    /**
     * @name 添加一次性任务
     */
    public function addOnceAction(){
        $app_name = $this->getParams('app_name');
        $command = $this->getParams('command');
        $note = $this->getParams('note');
        $retry = $this->getParams('retry');

        (new Job())->addOnce($app_name, $command, $note, $retry);
        $this->response['msg'] = '添加成功';
    }

    /**
     * @name 查看执行中任务界面
     */
    public function execJobViewAction(){
        $this->response = true;
    }

    /**
     * @name 执行中任务数据源
     */
    public function getExecJobAction(){
        $app_name = $this->getParams('app_name');

        $ret = (new \Admin\Model\Data\Job\Job())->getExecJob($app_name);
        $this->response['data'] = $ret;
    }

    /**
     * @name 更新定时任务的状态
     */
    public function updateTimedStatusAction(){
        $id = $this->getParams('id');
        (new AdminDataJob())->updateTimedStatus($id);
        $this->response['msg'] = '成功';
    }

    /**
     * @name 设置一次性任务为定时调度
     */
    public function setOnceForceAction(){
        $id = $this->getParams('id');
        (new AdminDataJob())->setOnceForce($id);
        $this->response['msg'] = '成功';
    }

    /**
     * @name 添加定时任务界面
     */
    public function addTimedViewAction(){
        $id = $this->getParams('id');
        if($id){
            $job = (new \Data\Job\Job())->getJob($id, \Dao\Db\Job\Log::TYPE_TIMED);
        }else{
            $job = array();
        }

        $this->setResponseFormat(\S\Response::FORMAT_PLAIN);
        $this->response = $this->getRenderView(null, array('job' => $job));
    }

    /**
     * @name 添加一次性任务界面
     */
    public function addOnceViewAction(){
        $this->setResponseFormat(\S\Response::FORMAT_PLAIN);
        $this->response = $this->getRenderView(null, array());
    }

    /**
     * @name  任务日志列表页
     */
    public function logAction(){
        $this->response = true;
    }

    /**
     * @name 任务列表的数据源
     */
    public function  logDataTableAction(){
        $job_id = $this->getParams('job_id');
        $type = $this->getParams('type');
        //根据任务id获取任务列表
        $job_log_list = (new \Admin\Model\Data\Job\Log())->queryDataTable($job_id, $type);
        $this->response = $job_log_list ?: array();
    }
}
