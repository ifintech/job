<?php
namespace Dao\Db\Job;

class Log extends \Dao\Db{

    const TYPE_TIMED = 0;
    const TYPE_ONCE = 1;

    const STATUS_HANDLING = 0;
    const STATUS_SUCC = 1;
    const STATUS_FAIL = 2;

    private $table;
    public function __construct(){
        $this->table = 'job_log';
    }

    public function add($data){
        return self::db()->insert($this->table, $data, true);
    }

    public function up($id, $data){
        $params = array(
            'id' => $id,
        );
        $ret = self::db()->update($this->table, $data, $params);
        return $ret;
    }

    public function getOneByJobId($job_id, $type, $cols = array()){
        $params = array(
            'job_id' => $job_id,
            'type' => $type
        );

        $ret = self::db()->queryone($this->table, $params, $cols);
        return $ret;
    }

    public function getOne($id, $cols = array()){
        $params = array(
            'id' => $id,
        );
        $ret = self::db()->queryone($this->table, $params, $cols);
        return $ret;
    }

    public function getByAppNameAndStatus($app_name, $status){
        $params = array(
            'app_name' => $app_name,
            'status'   => $status,
        );
        return self::db()->query($this->table, $params, array());
    }
}