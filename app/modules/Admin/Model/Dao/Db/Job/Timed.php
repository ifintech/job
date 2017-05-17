<?php
namespace Admin\Model\Dao\Db\Job;

use Dao\Db\Job\Timed as DbTimedJob;

class Timed extends \Modules\Admin\Model\Dao\Db\Db{

    const STATUS_ABLE = DbTimedJob::STATUS_ABLE;
    const STATUS_DISABLE = DbTimedJob::STATUS_DISABLE;

    private $table;

    public function __construct(){
        $this->table = 'job_timed';
    }

    public function queryDataTable($app_name){
        $params = array(
            'app_name' => $app_name,
        );
        return self::dataTable($this->table, $params, array(), array('status' => 'asc'));
    }

}
