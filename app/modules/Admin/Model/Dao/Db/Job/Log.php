<?php
namespace Admin\Model\Dao\Db\Job;

use Modules\Admin\Model\Dao\Db\Db as ModuleAdminDb;

class Log extends ModuleAdminDb{

    private $table;

    public function __construct(){
        $this->table = 'job_log';
    }

    public function queryDataTable($job_id,$type){
        $params = array(
            'job_id' => $job_id,
            'type' => $type,
        );
        return self::dataTable($this->table, $params, array(), array('id' => 'desc'));
    }
}