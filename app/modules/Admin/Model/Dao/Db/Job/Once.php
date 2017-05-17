<?php
namespace Admin\Model\Dao\Db\Job;

class Once extends \Modules\Admin\Model\Dao\Db\Db{

    private $table;

    public function __construct(){
        $this->table = 'job_once';
    }

    public function queryDataTable($app_name){
        $params = array(
            'app_name' => $app_name,
        );
        return self::dataTable($this->table, $params, array(), array('id' => 'desc'));
    }

}
